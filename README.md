# Filmlexikon | Projektfeladat

### Joó Tamás

Tolna Vármegyei SZC Perczel Mór Technikum és Kollégium

Szoftverfejlesztő- és tesztelő szakma

2024

## Technikai tudnivalók és a komponensek telepítése, beállítása

### Backend / API végpont kiszolgáló

A PHP/MySQL alapú API kiszolgáló a backend mappában található.

A főbb végpontokhoz írt teszteket a **backend/tests/ApiTest.php** tartalmazza, a tesztek eredménye pedig a **backend/tests/testresults.txt** -ben.

A tesztek PhpStorm-ban PHPUnit teszt keretrendszerrel készültek.

Adatbázismodell-diagram: **backend/database-diagram.png**

Telepítése: egy webszerveren végezzük el a következő lépéseket:
- a teljes **backend** mappa tartalmát másoljuk a webszerver megfelelő mappájába, pl. Xampp htdocs/filmlexikonapi, ahol a kliensek elérhetik az api.php végpontot, pl. http://localhost/filmlexikonapi
- hozzunk létre egy MySQL adatbázist és importáljuk bele a **backend/filmlexikon.sql** dump file-t
- töltsük ki a **config.php** elején lévő adatokat

Az API végpont a fenti példa alapján a következő: http://localhost/filmlexikonapi/api.php

Helyes telepítés után ennek a végpontnak már egy JSON üzenetet kell visszaadia, melyet böngészőben tesztelhetünk.

### Felhasználói frontend kliens

ReactJS alapú frontend kliens, a forrás file-ok a **frontend** mappában találhatók.

Fejlesztéshez, módosításhoz NodeJS telepítése szükséges, majd a mappába belépve az "npm install" paranccsal telepíthetjük a függőségeket, illetve az "npm start" paranccsal indíthatjuk el a ReactJS alkalmazást.

A használatra kész build a **frontend/build** mappában található, és a /filmlexikon almappára állítottam be, tehát jelenleg ebből az almappából tud futni.

Amennyiben ezt módosítani szerenénk, pl. docroot-ból ( / ) futtatni, akkor a NodeJS telepítése után, a package.json "homepage" beállítását átírva új buildet kell készítenünk.

Telepítés:
- a **frontend/build** mappa tartalmát másoljuk Xampp esetén a htdocs/filmlexikon mappába
- ebben találjuk a **filmlexikon.config** file-t, ahol adjuk meg az előző pontban beállított backend végpontot, pl. http://localhost/filmlexikonapi/api.php

A frontend használatra kész, pl. http://localhost/filmlexikon

### Adminisztrátori kliens

Az adminisztrátori kliens az **admin** mappában található Visual Studio projekt, amivel megnyitva teljeskörűen szerkeszthető a forrása.

Telepítés / indítás:
- a frontendhez hasonlóan itt is adjuk meg az API végpontot a **bin/Debug/filmlexikon.config.json** file-ban, az "apiBase"-be írjuk be az API elérhetőségét, pl. http://localhost/filmlexikonapi
- ezután indítható az admin kliens a **bin/Debug/filmlexikon.exe** -vel

## Dokumentáció
A projekt dokumentációja a **dokumentacio.pdf** -ben vagy [ezen a Google Drive linken](https://docs.google.com/document/d/1E4QRh4jaOvs4DvbWKQDskpf_yccLnIsGO3_VsS7Y7Gg){:target="_blank"} érhető el

## Online demó
A felhasználói frontend kliens [ide kattintva](https://filmlexikon.dev.impressive.hu){:target="_blank"} kipróbálható.
