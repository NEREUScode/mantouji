<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $productId)
    {
        // 1. Validation
        $request->validate([
            // Rating is now optional, but must be 1-5 if present (0 is allowed via controller logic)
            'comment' => 'nullable|string|max:500', 
            'rating'  => 'nullable|integer|min:0|max:5', 
        ]);

        // 2. Determine the submitted rating
        $submittedRating = (int)($request->rating ?? 0);
        
        // 3. ENFORCE ONE-REVIEW-PER-USER-PER-PRODUCT RULE (Reviews are comments with a rating > 0)
        if ($submittedRating > 0) {
            
            // Check if the user has already submitted a REVIEW (rating > 0) for this product
            $existingReview = Comment::where('user_id', Auth::id())
                                     ->where('product_id', $productId)
                                     ->where('rating', '>', 0) // CRITICAL: Check only for existing REVIEWS
                                     ->exists();

            if ($existingReview) {
                // If a review (with rating > 0) exists, block the new submission and return an error
                return redirect()->back()->with('error', 'Vous avez déjà soumis **une note (un avis)** pour ce produit. Vous ne pouvez attribuer une note qu\'une seule fois.');
            }
        }

        // 4. If no comment and no rating is provided, block the submission
        if (!$request->comment && $submittedRating < 1) {
            return redirect()->back()->withErrors('Veuillez fournir un commentaire ou une note.');
        }

        // 5. Create the new comment/review. This is allowed if:
        //    a) It's a review (rating > 0) AND no review exists, OR
        //    b) It's a simple comment (rating = 0)
        Comment::create([
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'comment' => $request->filled('comment') ? $request->comment : 'aucun commentaire',
            'rating'  => $submittedRating, // 0 if only a comment, 1-5 if a review
        ]);

        return redirect()->back()->with('success', 'Votre avis a été publié avec succès!');
    }
}