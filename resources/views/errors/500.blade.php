<link rel="stylesheet" href="{{ asset('css/error2.css') }}">
<body>
<section class="notFound">
    <div class="img">
        <img src="https://assets.codepen.io/5647096/backToTheHomepage.png" alt="Back to the Homepage"/>
        <img src="https://assets.codepen.io/5647096/Delorean.png" alt="El Delorean, El Doc y Marti McFly"/>
    </div>
    <div class="text">
        <h1>404</h1>
        <h2>Something went wrong</h2>
        <h3>Please back to admin website or add your rate in <a href="{{ route('contact_us') }}">Contact US</a></h3>
        <h3>BACK TO HOME?</h3>
        <a href="{{ route('home') }}" class="yes text-decoration-none">YES</a>
        <a href="#" onclick="history.back()" class="text-decoration-none">NO</a>
    </div>
</section>
</body>
