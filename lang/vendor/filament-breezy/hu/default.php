<?php

return [
    'login' => [
        'username_or_email' => 'Felhasználónév vagy e-mail cím',
        'forgot_password_link' => 'Elfelejtett jelszó?',
        'create_an_account' => 'fiók létrehozása',
    ],
    'password_confirm' => [
        'heading' => 'Jelszó megerősítése',
        'description' => 'Kérjük add meg a jelszavad, hogy vérehajthasd ezt a műveletet.',
        'current_password' => 'Jelenlegi jelszó',
    ],
    'two_factor' => [
        'heading' => 'Kétfaktoros hitelesítés',
        'description' => 'Kérjük add meg a hitelesítő alkalmazás által biztosított kódot!',
        'code_placeholder' => 'XXX-XXX',
        'recovery' => [
            'heading' => 'Kétfaktoros hitelesítés visszaállítása',
            'description' => 'Kérjük add meg a helyreállítási kódok egyikét!',
        ],
        'recovery_code_placeholder' => 'abcdef-98765',
        'recovery_code_text' => 'Elvesztetted a készüléked?',
        'recovery_code_link' => 'Használj helyreállítási kódot',
        'back_to_login_link' => 'Vissza a bejelentkezéshez',
    ],
    'registration' => [
        'title' => 'Regisztráció',
        'heading' => 'Fiók létrehozása',
        'submit' => [
            'label' => 'Regisztráció',
        ],
        'notification_unique' => 'Már létezik fiók ezzel az e-mail-címmel. Kérjük jelentkezz be!',
    ],
    'reset_password' => [
        'title' => 'Elfelejtett jelszó',
        'heading' => 'Jelszó visszaállítása',
        'submit' => [
            'label' => 'Küldés',
        ],
        'notification_error' => 'Hiba: próbáld újra később.',
        'notification_error_link_text' => 'Újrapróbálkozás',
        'notification_success' => 'Nézd meg az e-mail üzeneteid a további utasításokért!',
    ],
    'verification' => [
        'title' => 'E-mail cím megerősítés',
        'heading' => 'Az e-mail cím megerősítése szükkséges',
        'submit' => [
            'label' => 'Kijelentkezés',
        ],
        'notification_success' => 'Nézd meg az e-mail üzeneteid a további utasításokért!',
        'notification_resend' => 'Az ellenőrző e-mailt újra elküldtük.',
        'before_proceeding' => 'Mielőtt folytatnád, kérjük, ellenőrizd az e-mail üzeneteid.',
        'not_receive' => 'Ha nem kaptad meg az e-mailt,',
        'request_another' => 'kattints ide az újraküldéshez',
    ],
    'profile' => [
        'account' => 'Fiók',
        'profile' => 'Fiók',
        'my_profile' => 'Saját fiók',
        'subheading' => 'A saját felhasználói fiókod kezelése',
        'personal_info' => [
            'heading' => 'Fiók információk',
            'subheading' => 'Kérjük, add meg a fiókod adatait',
            'submit' => [
                'label' => 'Mentés',
            ],
            'notify' => 'A fiók sikeresen frissítve!',
        ],
        'password' => [
            'heading' => 'Jelszó',
            'subheading' => 'A jelszónak legalább 8 karakterből kell állnia.',
            'submit' => [
                'label' => 'Mentés',
            ],
            'notify' => 'A jelszó sikeresen frissítve!',
        ],
        '2fa' => [
            'title' => 'Kétfaktoros hitelesítés',
            'description' => 'Kétfaktoros hitelesítés beállítása (ajánlott).',
            'actions' => [
                'enable' => 'Bekapcsolás',
                'regenerate_codes' => 'Új kódok generálása',
                'disable' => 'Kikapcsolás',
                'confirm_finish' => 'Megerősítés és befejezés',
                'cancel_setup' => 'A beállítás megszakítása',
            ],
            'setup_key' => 'Beállítási kulcs',
            'not_enabled' => [
                'title' => 'Nincs bekapcsolva a kétfaktoros hitelesítés',
                'description' => 'Ha a kétfaktoros hitelesítést bekapcsolod, a rendszer a belépés után egy egyszeri, idő alapú kódot kér. Ezt a kódot a telefonod hitelesítő alkalmazásából (pl.: Google Authenticator) kérheted le.',
            ],
            'finish_enabling' => [
                'title' => 'Kétfaktoros hitelesítés bekapcsolásának befejezése',
                'description' => 'A kétfaktoros hitelesítés bekapcsolásának befejezéséhez olvasd be a következő QR-kódot telefonod hitelesítő alkalmazásával (pl.: Google Authenticator), vagy add meg a beállítási kulcsot, és végül add meg itt a generált egyszeri kódot.',
            ],
            'enabled' => [
                'title' => 'Be van kapcsolva a kétfaktoros hitelesítés',
                'description' => 'A kétfaktoros hitelesítés mostmár be van kapcsolva. Olvasd be a következő QR-kódot telefonod hitelesítő alkalmazásával, vagy írd be a beállítási kulcsot.',
                'store_codes' => 'Tárold ezeket a helyreállítási kódokat egy biztonságos helyen (pl.: egy külön papírlapra leírva). Ezek egyike szükséges a fiókodhoz való hozzáférés helyreállításához, ha a hitelesítési eszközöd elveszik.',
                'show_codes' => 'Helyreállítási kódok megjelenítése',
                'hide_codes' => 'Helyreállítási kódok elrejtése',
            ],
            'confirmation' => [
                'success_notification' => 'A kód ellenőrizve. Kétfaktoros hitelesítés bekapcsolva.',
                'invalid_code' => 'A megadott kód érvénytelen.',
            ],
        ],
        'sanctum' => [
            'title' => 'API Tokenek',
            'description' => 'Kezelje azokat az API-tokeneket, amelyek lehetővé teszik, hogy harmadik fél szolgáltatásai hozzáférjenek ehhez az alkalmazáshoz az Ön nevében. MEGJEGYZÉS: a token csak egyszer megjelenik a létrehozáskor. Ha elveszíti a tokent, törölnie kell, és újat kell létrehoznia.',
            'create' => [
                'notify' => 'Az API token létrehozva.',
                'submit' => [
                    'label' => 'Létrehozás',
                ],
            ],
            'update' => [
                'notify' => 'Az API token frissítve.',
            ],
        ],
    ],
    'clipboard' => [
        'link' => 'Kódok másolása',
        'tooltip' => 'Kimásolva!',
    ],
    'fields' => [
        'email' => 'E-mail cím',
        'login' => 'Bejelentkezés',
        'name' => 'Név',
        'password' => 'Jelszó',
        'password_confirm' => 'Jelszó megerősítése',
        'new_password' => 'Új jelszó',
        'new_password_confirmation' => 'Új jelszó megerősítése',
        'token_name' => 'Token neve',
        'abilities' => 'Képességek',
        '2fa_code' => 'Kód',
        '2fa_recovery_code' => 'Helyreállítási kód',
        'created' => 'Létrehozva',
        'expires' => 'Lejár',
    ],
    'or' => 'Vagy',
    'cancel' => 'Mégsem',
];
