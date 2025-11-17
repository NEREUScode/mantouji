<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/home.css">
    <title>الرئيسية - منتوجي</title>
</head>
<body>
    <div class="home">
        <x-navbar />
        <div class="content-container" id="Accueil">
            <div class="logo">
                <img src="/images/logo.png" alt="" srcset="">
            </div>
            <div class="content">
                <div class="left-content">
                    <p>ثروة فكيك في كل <span>منتج</span></p>
                    <p>تمور وحرف فكيك… أصالة تعبر الأجيال</p>
                </div>
            </div>
        </div>

        <div class="btn-home">
            <div class="btn-home-left"><a href="#service">التسجيل</a></div>
            <div class="btn-home-right">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}">مرحبا!</a>
                    @else
                        <a href="{{Route('login')}}">مسجل بالفعل؟</a>
                    @endauth
                @endif
            </div>
        </div>
    </div>

    <div class="guide-container">
        <div class="guide-left">
            <div class="guide-left-text">
                <p>دليلك إلى أفضل منتجات فكيك ✨</p>
            </div>
            <div class="guide-left-ste">
                <p>انتقل إلى</p>
                <p>🌐   Www.Mantouji.org</p>
            </div>
        </div>
        <div class="guide-right">
            <div class="guide-right-img-1">
                <img src="{{ asset('images/bg/fiig.PNG') }}" alt="" srcset="">
            </div>
            <!-- <div class="guide-right-img-2">
                <img src="{{ asset('images/bg/fiig-2.PNG') }}" alt="" srcset="">
            </div> -->
        </div>
    </div>

    <div class="etapes-header">
        <p>اكتشف بسرعة!</p>
    </div>
    <div class="etapes-container">
        <div class="etapes">
            <div class="etapes-parte-1">
                <div class="etapes-parte-1-header">
                    <p>الخطوة 1</p>
                    <p>اختر ملفك الشخصي</p>
                </div>
                <div class="etapes-parte-1-content">
                    <img src="/images/bg/etape 1.PNG" alt="" srcset="">
                    <p>اختر ملفك الشخصي: عميل للشراء، أو تعاونية لبيع منتجاتك</p>
                    <p>أنشئ حسابك ببضع نقرات للوصول إلى المنصة</p>
                </div>
            </div>
            <div class="etapes-parte-2">
                <img src="/images/bg/etape 1 circle.png" alt="" srcset="">
            </div>
        </div>

        <div class="etapes-r">
            <div class="etapes-parte-1">
                <div class="etapes-parte-1-header">
                    <p>الخطوة 2</p>
                    <p>إنشاء حسابك</p>
                </div>
                <div class="etapes-parte-1-content">
                    <img src="/images/bg/etape 2.PNG" alt="" srcset="">
                    <p class="etapes-parte-1-content-r">املأ استمارة التسجيل بمعلوماتك (الاسم، البريد الإلكتروني، الهاتف)</p>
                    <p class="etapes-parte-1-content-r">سيتم تفعيل حسابك فورا بعد التحقق</p>
                </div>
            </div>
            <div class="etapes-parte-2-r">
                <img src="/images/bg/etape 2 circle.png" alt="" srcset="">
            </div>
        </div>

        <div class="etapes">
            <div class="etapes-parte-1">
                <div class="etapes-parte-1-header">
                    <p>الخطوة 3</p>
                    <p>اترك آراءك</p>
                </div>
                <div class="etapes-parte-1-content">
                    <img src="/images/bg/etape 3.PNG" alt="" srcset="">
                    <p>شارك تجربتك وقيم المنتجات التي اشتريتها</p>
                    <p>آراؤك تساعد العملاء الآخرين وتثمن المنتجين المحليين</p>
                </div>
            </div>
            <div class="etapes-parte-2">
                <img src="/images/bg/etape 3 circle.png" alt="" srcset="">
            </div>
        </div>
    </div>

    <div class="figuig-to-you-container" id="propo">
        <div class="figuig-to-you"> 
            <p>من فكيك إليك</p>
        </div>
        <div class="figuig-to-you-content">
            <p>
                منتوجي منصة مخصصة لتثمين المنتجات الزراعية والغذائية والحرفية، مع تجذر قوي في منطقة فكيك.
تربط مباشرة بين المنتجين والتعاونيات والحرفيين المحليين مع المستهلكين والشركاء، مع ضمان الأصالة والتتبع والجودة.
            </p>
            <p>
                هدفنا هو دعم الاقتصاد المحلي، إبراز هوية فكيك التراثية، وتقديم منتجات عالية الجودة مباشرة من المنتج إلى المستهلك.
            </p>

            <div class="ligne">
                <div class="ligne-container">
                    <img src="/images/bg/ligne.jpeg" alt="" srcset="">
                    <img src="/images/bg/ligne.jpeg" alt="" srcset="">
                </div>
            </div>
        </div>
        {{-- <div class="ligne">
            <div class="ligne-container">
                <img src="/images/bg/ligne.jpeg" alt="" srcset="">
                <img src="/images/bg/ligne.jpeg" alt="" srcset="">
            </div>
        </div> --}}
    </div>
    <div class="auth" id="service">
        <div class="auth-header">
            <p>أخبرنا من أنت!</p>
        </div>
        <div class="auth-cards">
            <div class="card">
                <div class="card-content-header">
                    <div class="card-content">
                        <div class="card-container-image">
                            <img src="/images/icones/client.png" alt="" srcset="">
                        </div>
                        <p><a href="{{Route('register', ['type' => 0])}}">عميل؟</a></p>
                    </div>
                </div>
            </div>
            <div class="auth-card-or">
                <p>أو</p>
            </div>
            <div class="card">
                <div class="card-content-header">
                    <div class="card-content">
                        <div class="card-container-image">
                            <img src="/images/icones/store.png" alt="" srcset="">
                        </div>
                        <p><a href="{{Route('register', ['type' => 1])}}">تعاونية؟</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer" id="conta">
        <div class="footer-container">
            <div class="footer-logo">
                <img src="/images/logo.png" alt="" srcset="">
            </div>
            <div class="footer-links-container">
                <div class="footer-links">
                    <ul>
                        <li style="font-weight: bold">معلومات</li>
                        <li style="margin-top: 15px"><a href="#propo">عن منتوجي</a></li>
                        <li><a href="#conta">اتصل</a></li>
                        <li><a href="#">شروط الاستخدام</a></li>
                        <li><a href="#">سياسة الخصوصية</a></li>
                    </ul>
                </div>

                <div class="footer-links">
                    <ul>
                        <li style="font-weight: bold">روابط مفيدة</li>
                        <li style="margin-top: 15px"><a href="#acc">الرئيسية</a></li>
                        <li><a href="#propo">عن المنصة</a></li>
                        <li><a href="#service">الخدمات</a></li>
                        <li><a href="#conta">اتصل</a></li>
                    </ul>
                </div>

                <div class="footer-links">
                    <ul>
                        <li style="font-weight: bold">اتصل</li>
                        <li style="margin-top: 15px"><a href="https://www.tech-da.com/">Tech-da</a></li>
                        {{-- <li>Home</li>
                        <li>Home</li>
                        <li>Home</li> --}}
                    </ul>
                </div>
            </div>

            <div class="footer-logo">
                <img src="/images/bg/footer.png" alt="" srcset="" style="border-radius: 12px; padding-top: 30px; padding-bottom: 15px;">
            </div>
        </div>
        <hr style="width: 90%; margin: auto; margin-top: 20px; border: 0.5px solid gray">
        <div class="footer-bottom">
            <div>
                <p>Copyright</p>
            </div>
            <div class="footer-terms">
                <ul>
                    <li>الشروط</li>
                    <li>الخصوصية</li>
                    <li>سياسة ملفات تعريف الارتباط</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="js/navbar.js"></script>
</body>
</html>