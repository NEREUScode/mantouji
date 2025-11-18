<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/jammiya.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>لوحة التحكم - منتوجي</title>
</head>
<body>
    <nav class="navbar">
        <h1 class="logo">Mantouji</h1>
        <button id="menu-toggle" class="menu-btn">☰</button>
    </nav>

    <div class="space-x"></div>

    <div id="sidebar" class="sidebar">
        <button class="side-btn">عرض المنتجات</button>
        <button id="openModal" class="side-btn">إضافة منتج</button>
        <button id="openModal" class="side-btn"><a href="{{Route('viewPageInfo')}}">تعديل les معلوماتs</a></button>
        <button id="openModal" class="side-btn"><a href="{{Route('profile.edit')}}">الملف الشخصي</a></button>
        <div class="sidebar-content">
            <div class="sidebar-logo">
                <img src="/images/logo.png" alt="" srcset="">
            </div>
            <div class="sidebar-footer">
                <div>
                    <div class="sidebar-footer-item">
                        <form action="{{Route('logout')}}" method="post">
                            @csrf
                            <button type="submit">تسجيل الخروج</button>
                        </form>
                    </div>
                    <div class="sidebar-footer-item">
                        <a href="{{Route('home')}}">Acceuil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="productModal" class="modal">
        <div class="modal-content">
            <span id="closeModal" class="close">&times;</span>
            <h2>إضافة منتج جديد</h2>
            <form action="{{ Route('addProduct') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="file" class="input" name="image" id="imageInput">
                <img id="previewImage" src="" alt="Preview" style="max-width:150px; margin-top:10px; display:none;">
                <input type="text" placeholder="الاسم du منتج" class="input" name="name">
                <button class="add-btn" type="submite">إضافة المنتج</button>
            </form>
        </div>
    </div>

   <div class="main-content">
        <div class="header">
            <p>المنتجات</p>
        </div>

        <div class="content">
            @foreach ($products as $product)
                <div class="product-card">                                  
                    <img src="{{ asset('storage/'.$product->image) }}" alt="Product Image" class="product-img">
                    <h3 class="product-name">{{ $product->name }}</h3>

                    <div class="product-rating">
                        @for ($i = 0; $i < $product->reviews; $i++)
                            <span>★</span>
                        @endfor
                        <div style="padding-left: 4px; padding-top: 4px; font-size: 14px; color: #555;">
                            ({{ $product->reviews_number }})
                        </div>
                    </div>

                    <div class="product-actions">
                        <a href="{{ Route('editeProduct', $product->id)}}"><img src="/images/icones/pen.png" alt="" style="width : 35px"></a> 
                            <form action="{{Route('deleteProduct', $product->id)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="delete-btn"><img src="/images/icones/delete.png" alt=""   style="width : 35px"></button>
                            </form>
                        </div>
                    
                          <button class="show-comments-btn"
                                onclick="toggleComments('comments-{{ $product->id }}')">
                            Show Comments
                        </button>
                    <div id="comments-{{ $product->id }}" class="comments-box">
                        @foreach ($product->comments as $comment)
                            <div class="comment-item">
                                <strong>{{ $comment->user->name }}</strong>
                                <p>{{ $comment->comment }}</p>
                            </div>
                        @endforeach

                        @if ($product->comments->count() == 0)
                            <p>No comments yet </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
   </div>

    <script src="{{asset('js/jammiya.js')}}"></script>

</body>
</html>