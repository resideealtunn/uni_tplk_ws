<html lang="tr">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
      integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link
      rel="shortcut icon"
      type="image/x-icon"
      href="images/bilisimlogo.png"
    />
	<link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}" />
    <title>{{request('topluluk','Topluluk Seçilmedi')}}</title>
   <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  </head>

  <body>
	<nav class="navbar navbar-expand-lg bg-body-tertiary header">
  <div class="container-fluid">
    <a class="navbar-brand logo" href="{{route('tpl_anasayfa')}}">
      <img src="images/bilisimlogo.png" alt="logo" />
    </a>
    <div class="collapse navbar-collapse navbar" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="{{route('tpl_anasayfa')}}">Ana Sayfa</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('tpl_etkinlik')}}">Etkinlikler</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('tpl_uye')}}">Üye İşlemleri</a>
        </li>
      </ul>
      <div class="buttons">
        <!--navbarın kenarındaki iconlar için-->
      </div>
    </div>
  </div>
</nav>

    <!--! Home Section Start-->
    <section class="home">
      <div class="content">
        <h6>NECMETTİN ERBAKAN ÜNİVERSİTESİ</h6>
        <h3>BİLİŞİM TOPLULUĞU</h3>
        <h4>GELECEĞİ BERABER ŞEKİLLENDİRECEĞİZ</h4>
      </div>
    </section>

    <!--! About Us -->
    <section class="about">
      <h1 class="heading" style='color:white'>HAKKIMIZDA</h1>
      <div class="row">
        <div class="image">
          <img src="./images/aboutus.jpg" alt="about" />
        </div>
        <div class="content">
          <h3>Necmettin Erbakan Üniversitesi : Bilişim Topluluğu</h3>
          <p>
            2019 yılında kurulan topluluğumuz, kurulduğu günden bu yana ulusal
            ve uluslararası alanda birçok etkinlik ve eğitim düzenleyerek, hem
            üniversitemiz hem de daha geniş çevrelerde fark yaratmaya devam
            etmektedir. Topluluğumuz, çeşitli organizasyonlar ve eğitim
            programları aracılığıyla bilgi paylaşımı ve deneyim kazandırma
            misyonunu sürdürmektedir. Katılımcılarımıza sunduğumuz geniş
            kapsamlı etkinlikler, geziler ve eğitimlerle, topluluğumuza dahil
            olarak siz de bu dinamik ve zengin programlardan
            yararlanabilirsiniz. Hem kişisel hem de profesyonel gelişiminize
            katkıda bulunacak bu fırsatlar, aynı zamanda sizi farklı alanlarda
            yetkinlik kazandıracak etkinliklere yönlendirecektir. Ayrıca,
            topluluğumuzun yönetim ekibine katılarak, düzenlediğimiz çeşitli
            faaliyetlerde görev alabilir, projelerimizi daha da ileriye taşıma
            konusunda aktif bir rol üstlenebilirsiniz. Bu fırsat, sadece
            topluluğumuza katkıda bulunmanızı değil, aynı zamanda liderlik ve
            organizasyon becerilerinizi geliştirme şansı sunmaktadır.
            Topluluğumuzun bir parçası olarak, hem kişisel hem de topluluk
            bazında önemli deneyimler kazanabilirsiniz. Sizleri de aramızda
            görmekten mutluluk duyarız!
          </p>
          <a href="uyeislemleri" class="btn">Üye İşlemleri İçin</a>
        </div>
      </div>
    </section>

    <!--! contact-->
    <!--! contact-->
    <section class="contact">
      <!-- <h1 class="heading">İLETİŞİM</h1> -->
      <p class="contact-info">
        * Sorularınızı, düşüncelerinizi, geri bildirimlerinizi bu alan ile bize
        ulaştırabilirsiniz
      </p>

      <div class="row">
        <div class="map">
          <img src="./images/maptoimage.jpeg" alt="Map Image" />
        </div>
        <form method='POST'>
          <h1>İLETİŞİM</h1>
          <div class="inputBox">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="İsim Soyisim" name='isim' />
          </div>
          <div class="inputBox">
            <i class="fas fa-envelope"></i>
            <input type="email" placeholder="Mail" name='mail' />
          </div>
          <div class="inputBox">
            <i class="fas fa-comment"></i>
            <textarea placeholder="Mesajınız" name='mesaj'></textarea>
          </div>
          <input
            type="submit"
            class="btn"
            id="btn"
            value="Geri Bildirim Gönder"
			name='btn';
          />
        </form>
      </div>
    </section>
    <!--! footer -->
    <footer class="footer py-3 my-4">
      <div class="share">
        <a
		style='text-decoration: none;'
          href="https://chat.whatsapp.com/CNoPPLXvyTJAQhkft0nkc0"
          class="fab fa-whatsapp"
        ></a>
        <a
		style='text-decoration: none;'
          href="https://github.com/neubilisimtoplulugu"
          class="fab fa-github"
        ></a>
        <a
		style='text-decoration: none;'
          href="https://www.instagram.com/neubilisimtoplulugu/"
          class="fab fa-instagram"
        ></a>
        <a
style='text-decoration: none;'		
		href="https://www.linkedin.com/company/necmettin-erbakan-%C3%BCniversitesi-bili%C5%9Fim-toplulu%C4%9Fu/?viewAsMember=true" class="fab fa-linkedin"></a>
      </div>
      <div class="links nav justify-content-center border-bottom pb-3 mb-3 ">
        <a href="{{route('tpl_anasayfa')}}" class="nav-link">Ana Sayfa</a>
        <a href="{{route('tpl_etkinlik')}}" class="nav-link">Etkinlikler</a>
        <a href="{{route('tpl_uye')}}" class="nav-link">Üye İşlemleri</a>
      </div>
      <div class="credit">
        <p class="text-center">© 2024 Bilişim Topluluğu, Tüm Hakları Saklıdır.</p>
      </div>
    </footer>
  </body>
</html>
