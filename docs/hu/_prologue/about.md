---
group: Bevezetés
title: A rendszerről röviden
icon: heroicon-o-information-circle
order: 1
---

## A projektről

A rendszer a 2024/2025-ös tanévben megszervezett Dusza Árpád Országos Programozói Emlékversenyre készült, ennek megfelelően a versenykiírások és a versenyek kezelésére lett kialakítva. A rendszer a verseny szervezését, a versenyzők és csapatok kezelését, valamint a versenykiírás közzétételét és kezelését segíti.

## Fejlesztések a követelményrendszerhez képest

> _Azaz, hogy gondolkodtunk mi_

-   **Csapatok**: A csapathoz való kapcsolat semmilyen esetben nem része a felhasználónak, mely az alábbi előnyökkel jár
    -   A csapatokhoz tartozó "Versenyzői Profilok" (továbbiakban: profilok) nem függnek a felhasználótól, így lehetséges úgy csapattagot hozzáadni, hogy az nem regisztrált a rendszerbe.
        > Ettől függetlenül, a regisztrálás lehetőslége megmarad, a lentebb leírt meghívókódos rendszerrel.
    -   Tanár típusú profil esetén N tanárhoz N csapatot rendelhetünk, ugyanis egy tanárnak több csapata is lehet.
        > _Illetve, mivel egy tanár egyszerre esetleg akár több intézményben is taníthat, tanárok esetén N tanárhoz N iskola tartozhat_
-   **Iskolák**: Az iskolákhoz tartozó profilokat habár a szervezők kezelik, és eleinte egy "kapcsolattartó" felhasználóval rendelkeznek, az iskolai adminisztrátorok is képesek egyéb adminisztrátorokat meghívni.

    > _Így egy iskolához bármennyi adminisztrátor rendelhető, akik egyenrangú jogokkal rendelkeznek, megkönnyítve ezzel a csapaktok jelentkezésének jóváhagyását._

## Hitelesítés

A rendszer 4 különböző felülettel (továbbiakban: panelek) rendelkezik, melyeket különböző jogkörökkel rendelkező felhasználók használhatnak. A panelek a következők:

-   **Versenyzői panel**: A versenyzők itt regisztrálhatnak, kezelhetik a fiókjukat, illetve a versenyekre való jelentkezés is itt zajlik, valamit itt követhetik a jelenlegi versenykiírást.
-   **Szervezői panel**: A szervezők itt kezelhetik a versenyzőket, az iskolákat, a kategóriákat, illetve a programnyelveket. Továbbá itt állíthatják be a verseny határidejét és a kiírását. Emellett különféle statisztikákhoz is hozzáférnek.
-   **Iskolai panel**: Az iskolák itt kezelhetik a regisztrált adataikat, valamint módosíthatják is ezeket. Az iskolák itt tudják megtekinteni a csapatokat, és jóvá tudják hagyni a csapatok jelentkezését.
-   **Tanári panel**: A tanárok itt kezelhetik a csapatokat, amelyeknek felkészítő tanáraik.

### Regisztrációs módok

-   **Felhasználónév és jelszó**: A felhasználók ezzel a módszerrel tudnak bejelentkezni a rendszerbe.
-   **Meghívás E-mailben**: A rendszer automatikusan küld egy meghívót a felhasználóknak, amelyben egy link található, amelyre kattintva a felhasználó be tud regisztrálni a rendszerbe.
    > Megjegyzés: Ez a meghívókód minden esetben kiküldődik, amikor egy csapatot létrehoznak, melynek a résztvevőihez tartoznak E-mail címek.
    >
    > _Továbbá használható további szervezők és iskolai adminisztrátorok meghívására_
