<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Google Maps</title>
  <link rel="stylesheet" href="{{ asset('gm/style.css') }}">

  <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
</head>

<body>

  <form action="#" method="POST">
    <input type="text" placeholder="اكتب عنوانك" required>
    <input type="hidden" name="latitude" value="">
    <input type="hidden" name="longitude" value="">

    <select name="region" id="region">
      <option value="0">حي الملز</option>option>
      <option value="1">حي الفاخرية</option>option>
      <option value="2">حي البطحاء</option>option>
      <option value="3">حي المرقب</option>option>
      <option value="4">حي المربع</option>option>
      <option value="5">حي الصالحية</option>option>
      <option value="6">وحي الديرة</option>option>
    </select>

    <!-- Google Map Container -->
    <!-- Here we display the map -->
    <p>حدد المكان على الخريطة</p>
    <div id="map"></div>

    <button type="submit">إرسال</button>
  </form>

  <!-- Include all required scripts -->
  <!-- Source: https://developers.google.com/maps -->
  <script>(g => { var h, a, k, p = "The Google Maps JavaScript API", c = "google", l = "importLibrary", q = "__ib__", m = document, b = window; b = b[c] || (b[c] = {}); var d = b.maps || (b.maps = {}), r = new Set, e = new URLSearchParams, u = () => h || (h = new Promise(async (f, n) => { await (a = m.createElement("script")); e.set("libraries", [...r] + ""); for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]); e.set("callback", c + ".maps." + q); a.src = `https://maps.${c}apis.com/maps/api/js?` + e; d[q] = f; a.onerror = () => h = n(Error(p + " could not load.")); a.nonce = m.querySelector("script[nonce]")?.nonce || ""; m.head.append(a) })); d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() => d[l](f, ...n)) })
      ({ key: "AIzaSyB41DRUbKWJHPxaFjMAwdrzWzbVKartNGg", v: "weekly" });</script>

  <script src="{{ asset('gm/script.js') }}"></script>
</body>

</html>
