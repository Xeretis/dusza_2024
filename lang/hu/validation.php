<?php

declare(strict_types=1);

return [
    'accepted'             => 'A(z) :attribute el kell legyen fogadva!',
    'accepted_if'          => 'A :attribute-at el kell fogadni, amikor a :other a(z) :value.',
    'active_url'           => 'A(z) :attribute nem érvényes url!',
    'after'                => 'A(z) :attribute :date utáni dátum kell, hogy legyen!',
    'after_or_equal'       => 'A(z) :attribute nem lehet korábbi dátum, mint :date!',
    'alpha'                => 'A(z) :attribute kizárólag betűket tartalmazhat!',
    'alpha_dash'           => 'A(z) :attribute kizárólag betűket, számokat és kötőjeleket tartalmazhat!',
    'alpha_num'            => 'A(z) :attribute kizárólag betűket és számokat tartalmazhat!',
    'array'                => 'A(z) :attribute egy tömb kell, hogy legyen!',
    'ascii'                => 'A(z) :attribute csak egybájtos alfanumerikus karaktereket és szimbólumokat tartalmazhat.',
    'before'               => 'A(z) :attribute :date előtti dátum kell, hogy legyen!',
    'before_or_equal'      => 'A(z) :attribute nem lehet későbbi dátum, mint :date!',
    'between'              => [
        'array'   => 'A(z) :attribute :min - :max közötti elemet kell, hogy tartalmazzon!',
        'file'    => 'A(z) :attribute mérete :min és :max kilobájt között kell, hogy legyen!',
        'numeric' => 'A(z) :attribute :min és :max közötti szám kell, hogy legyen!',
        'string'  => 'A(z) :attribute hossza :min és :max karakter között kell, hogy legyen!',
    ],
    'boolean'              => 'A(z) :attribute mező csak igaz vagy hamis értéket kaphat!',
    'can'                  => 'A(z) :attribute-es mező nem engedélyezett értéket tartalmaz.',
    'confirmed'            => 'A(z) :attribute nem egyezik a megerősítéssel.',
    'contains'             => 'A(z) :attribute mezőből hiányzik egy kötelező érték.',
    'current_password'     => 'A(z) jelszó helytelen.',
    'date'                 => 'A(z) :attribute nem érvényes dátum.',
    'date_equals'          => ':Attribute meg kell egyezzen a következővel: :date.',
    'date_format'          => 'A(z) :attribute nem egyezik az alábbi dátum formátummal :format!',
    'decimal'              => 'A(z) :attribute-nak :decimal tizedesjegynek kell lennie.',
    'declined'             => 'A(z) :attribute-at el kell utasítanod.',
    'declined_if'          => 'A(z) :attribute-at el kell utasítanod, ha a :other a(z) :value.',
    'different'            => 'A(z) :attribute és :other értékei különbözőek kell, hogy legyenek!',
    'digits'               => 'A(z) :attribute :digits számjegyű kell, hogy legyen!',
    'digits_between'       => 'A(z) :attribute értéke :min és :max közötti számjegy lehet!',
    'dimensions'           => 'A(z) :attribute felbontása nem megfelelő.',
    'distinct'             => 'A(z) :attribute értékének egyedinek kell lennie!',
    'doesnt_end_with'      => 'A(z) :attribute nem végződhet a következők egyikével: :values.',
    'doesnt_start_with'    => 'A(z) :attribute nem kezdődhet a következők egyikével: :values.',
    'email'                => 'A(z) :attribute nem érvényes email formátum.',
    'ends_with'            => 'A(z) :attribute a következővel kell végződjön: :values',
    'enum'                 => 'A kiválasztott :attribute érvénytelen.',
    'exists'               => 'A kiválasztott :attribute érvénytelen.',
    'extensions'           => 'A(z) :attribute-es mezőnek a következő kiterjesztések valamelyikével kell rendelkeznie: :values.',
    'file'                 => 'A(z) :attribute fájl kell, hogy legyen!',
    'filled'               => 'A(z) :attribute megadása kötelező!',
    'gt'                   => [
        'array'   => 'A(z) :attribute több, mint :value elemet kell, hogy tartalmazzon.',
        'file'    => 'A(z) :attribute mérete nagyobb kell, hogy legyen, mint :value kilobájt.',
        'numeric' => 'A(z) :attribute nagyobb kell, hogy legyen, mint :value!',
        'string'  => 'A(z) :attribute hosszabb kell, hogy legyen, mint :value karakter.',
    ],
    'gte'                  => [
        'array'   => 'A(z) :attribute legalább :value elemet kell, hogy tartalmazzon.',
        'file'    => 'A(z) :attribute mérete nem lehet kevesebb, mint :value kilobájt.',
        'numeric' => 'A(z) :attribute nagyobb vagy egyenlő kell, hogy legyen, mint :value!',
        'string'  => 'A(z) :attribute hossza nem lehet kevesebb, mint :value karakter.',
    ],
    'hex_color'            => 'A(z) :attribute-es mezőnek érvényes hexadecimális színnek kell lennie.',
    'image'                => 'A(z) :attribute képfájl kell, hogy legyen!',
    'in'                   => 'A kiválasztott :attribute érvénytelen.',
    'in_array'             => 'A(z) :attribute értéke nem található a(z) :other értékek között.',
    'integer'              => 'A(z) :attribute értéke szám kell, hogy legyen!',
    'ip'                   => 'A(z) :attribute érvényes IP cím kell, hogy legyen!',
    'ipv4'                 => 'A(z) :attribute érvényes IPv4 cím kell, hogy legyen!',
    'ipv6'                 => 'A(z) :attribute érvényes IPv6 cím kell, hogy legyen!',
    'json'                 => 'A(z) :attribute érvényes JSON szöveg kell, hogy legyen!',
    'list'                 => 'A(z) :attribute-es mezőnek listának kell lennie.',
    'lowercase'            => 'A(z) :attribute-nak kisbetűnek kell lennie.',
    'lt'                   => [
        'array'   => 'A(z) :attribute kevesebb, mint :value elemet kell, hogy tartalmazzon.',
        'file'    => 'A(z) :attribute mérete kisebb kell, hogy legyen, mint :value kilobájt.',
        'numeric' => 'A(z) :attribute kisebb kell, hogy legyen, mint :value!',
        'string'  => 'A(z) :attribute rövidebb kell, hogy legyen, mint :value karakter.',
    ],
    'lte'                  => [
        'array'   => 'A(z) :attribute legfeljebb :value elemet kell, hogy tartalmazzon.',
        'file'    => 'A(z) :attribute mérete nem lehet több, mint :value kilobájt.',
        'numeric' => 'A(z) :attribute kisebb vagy egyenlő kell, hogy legyen, mint :value!',
        'string'  => 'A(z) :attribute hossza nem lehet több, mint :value karakter.',
    ],
    'mac_address'          => 'A(z) :attribute-nak érvényes MAC-címnek kell lennie.',
    'max'                  => [
        'array'   => 'A(z) :attribute legfeljebb :max elemet kell, hogy tartalmazzon.',
        'file'    => 'A(z) :attribute mérete nem lehet több, mint :max kilobájt.',
        'numeric' => 'A(z) :attribute értéke nem lehet nagyobb, mint :max!',
        'string'  => 'A(z) :attribute hossza nem lehet több, mint :max karakter.',
    ],
    'max_digits'           => 'A(z) :attribute nem lehet több :max számjegynél.',
    'mimes'                => 'A(z) :attribute kizárólag az alábbi fájlformátumok egyike lehet: :values.',
    'mimetypes'            => 'A(z) :attribute kizárólag az alábbi fájlformátumok egyike lehet: :values.',
    'min'                  => [
        'array'   => 'A(z) :attribute legalább :min elemet kell, hogy tartalmazzon.',
        'file'    => 'A(z) :attribute mérete nem lehet kevesebb, mint :min kilobájt.',
        'numeric' => 'A(z) :attribute értéke nem lehet kisebb, mint :min!',
        'string'  => 'A(z) :attribute hossza nem lehet kevesebb, mint :min karakter.',
    ],
    'min_digits'           => 'A(z) :attribute-nak legalább :min számjegyből kell állnia.',
    'missing'              => 'A(z) :attribute-as mezőnek hiányoznia kell.',
    'missing_if'           => 'A(z) :attribute-as mezőnek hiányoznia kell, ha a :other az :value.',
    'missing_unless'       => 'A(z) :attribute-as mezőnek hiányoznia kell, hacsak a :other nem :value.',
    'missing_with'         => 'A(z) :attribute-as mezőnek hiányoznia kell, ha a :values szerepel.',
    'missing_with_all'     => 'A(z) :attribute mezőnek hiányoznia kell, ha :values van jelen.',
    'multiple_of'          => 'A(z) :attribute :value többszörösének kell lennie',
    'not_in'               => 'A(z) :attribute értéke érvénytelen.',
    'not_regex'            => 'A(z) :attribute formátuma érvénytelen.',
    'numeric'              => 'A(z) :attribute szám kell, hogy legyen!',
    'password'             => [
        'letters'       => 'A(z) :attribute-nak legalább egy betűt kell tartalmaznia.',
        'mixed'         => 'A(z) :attribute-nak legalább egy nagybetűt és egy kisbetűt kell tartalmaznia.',
        'numbers'       => 'A(z) :attribute-nak legalább egy számot kell tartalmaznia.',
        'symbols'       => 'A(z) :attribute-nak legalább egy szimbólumot kell tartalmaznia.',
        'uncompromised' => 'Adatszivárgásban jelent meg az adott :attribute. Kérjük, válasszon másik :attribute-at.',
    ],
    'present'              => 'A(z) :attribute mező nem található!',
    'present_if'           => 'A(z) :attribute-es mezőnek jelen kell lennie, ha a :other az :value.',
    'present_unless'       => 'A(z) :attribute-es mezőnek jelen kell lennie, kivéve, ha a :other az :value.',
    'present_with'         => 'A(z) :attribute-es mezőnek jelen kell lennie, ha :values van jelen.',
    'present_with_all'     => 'A(z) :attribute mezőnek jelen kell lennie, ha :values van jelen.',
    'prohibited'           => 'A(z) :attribute mező tilos.',
    'prohibited_if'        => 'A(z) :attribute mező tilos, ha :other :value.',
    'prohibited_unless'    => 'A(z) :attribute mező tilos, kivéve, ha :other a :values.',
    'prohibits'            => 'A(z) :attribute mező tiltja, hogy :other jelen legyen.',
    'regex'                => 'A(z) :attribute formátuma érvénytelen.',
    'required'             => 'A(z) :attribute megadása kötelező!',
    'required_array_keys'  => 'A(z) :attribute-as mezőnek a következő bejegyzéseket kell tartalmaznia: :values.',
    'required_if'          => 'A(z) :attribute megadása kötelező, ha a(z) :other értéke :value!',
    'required_if_accepted' => 'A(z) :attribute-as mező kitöltése kötelező, ha elfogadod a(z) :other-t.',
    'required_if_declined' => 'A(z) :attribute mező kitöltése kötelező ha visszautasítod a(z) :other-t.',
    'required_unless'      => 'A(z) :attribute megadása kötelező, ha a(z) :other értéke nem :values!',
    'required_with'        => 'A(z) :attribute megadása kötelező, ha a(z) :values érték létezik.',
    'required_with_all'    => 'A(z) :attribute megadása kötelező, ha a(z) :values értékek léteznek.',
    'required_without'     => 'A(z) :attribute megadása kötelező, ha a(z) :values érték nem létezik.',
    'required_without_all' => 'A(z) :attribute megadása kötelező, ha egyik :values érték sem létezik.',
    'same'                 => 'A(z) :attribute és :other mezőknek egyezniük kell!',
    'size'                 => [
        'array'   => 'A(z) :attribute :size elemet kell tartalmazzon!',
        'file'    => 'A(z) :attribute mérete :size kilobájt kell, hogy legyen!',
        'numeric' => 'A(z) :attribute értéke :size kell, hogy legyen!',
        'string'  => 'A(z) :attribute hossza :size karakter kell, hogy legyen!',
    ],
    'starts_with'          => ':Attribute a következővel kell kezdődjön: :values',
    'string'               => 'A(z) :attribute szöveg kell, hogy legyen.',
    'timezone'             => 'A(z) :attribute nem létező időzona.',
    'ulid'                 => 'A(z) :attribute-nak érvényes ULID-nek kell lennie.',
    'unique'               => 'A(z) :attribute már foglalt.',
    'uploaded'             => 'A(z) :attribute feltöltése sikertelen.',
    'uppercase'            => 'A(z) :attribute-nak nagybetűnek kell lennie.',
    'url'                  => 'A(z) :attribute érvénytelen link.',
    'uuid'                 => ':Attribute érvényes UUID-val kell rendelkezzen.',
    'attributes'           => [
        'address'                  => 'cím',
        'affiliate_url'            => 'társult URL',
        'age'                      => 'kor',
        'amount'                   => 'összeg',
        'announcement'             => 'közlemény',
        'area'                     => 'terület',
        'audience_prize'           => 'közönségdíj',
        'audience_winner'          => 'közönségkedvenc',
        'available'                => 'elérhető',
        'birthday'                 => 'születésnap',
        'body'                     => 'test',
        'city'                     => 'város',
        'company'                  => 'cég',
        'compilation'              => 'összeállítás',
        'concept'                  => 'koncepció',
        'conditions'               => 'körülmények',
        'content'                  => 'tartalom',
        'contest'                  => 'verseny',
        'country'                  => 'ország',
        'cover'                    => 'borító',
        'created_at'               => 'létrehozás dátuma',
        'creator'                  => 'készítő',
        'currency'                 => 'valuta',
        'current_password'         => 'jelenlegi jelszó',
        'customer'                 => 'vevő',
        'date'                     => 'dátum',
        'date_of_birth'            => 'születési dátum',
        'dates'                    => 'dátumok',
        'day'                      => 'nap',
        'deleted_at'               => 'törlés dátuma',
        'description'              => 'leírás',
        'display_type'             => 'kijelző típusa',
        'district'                 => 'kerület',
        'duration'                 => 'időtartam',
        'email'                    => 'email',
        'excerpt'                  => 'kivonat',
        'filter'                   => 'szűrő',
        'finished_at'              => 'befejezés dátuma',
        'first_name'               => 'keresztnév',
        'gender'                   => 'neme',
        'grand_prize'              => 'fődíj',
        'group'                    => 'csoport',
        'hour'                     => 'óra',
        'image'                    => 'kép',
        'image_desktop'            => 'asztali kép',
        'image_main'               => 'fő kép',
        'image_mobile'             => 'mobil kép',
        'images'                   => 'képek',
        'is_audience_winner'       => 'közönséggyőztes-e',
        'is_hidden'                => 'rejtve van-e',
        'is_subscribed'            => 'elő van-e fizetve',
        'is_visible'               => 'látható-e',
        'is_winner'                => 'győztes-e',
        'items'                    => 'tételek',
        'key'                      => 'kulcs',
        'last_name'                => 'vezetéknév',
        'lesson'                   => 'lecke',
        'line_address_1'           => 'cím 1. sora',
        'line_address_2'           => 'cím 2. sora',
        'login'                    => 'belépés',
        'message'                  => 'üzenet',
        'middle_name'              => 'középső név',
        'minute'                   => 'perc',
        'mobile'                   => 'mobil',
        'month'                    => 'hónap',
        'name'                     => 'név',
        'national_code'            => 'nemzeti kód',
        'number'                   => 'szám',
        'password'                 => 'jelszó',
        'password_confirmation'    => 'jelszó megerősítése',
        'phone'                    => 'telefon',
        'photo'                    => 'fénykép',
        'portfolio'                => 'portfólió',
        'postal_code'              => 'irányítószám',
        'preview'                  => 'előnézet',
        'price'                    => 'ár',
        'product_id'               => 'termék azonosító',
        'product_uid'              => 'termék UID',
        'product_uuid'             => 'termék UUID',
        'promo_code'               => 'promóciós kód',
        'province'                 => 'tartomány',
        'quantity'                 => 'mennyiség',
        'reason'                   => 'ok',
        'recaptcha_response_field' => 'recaptcha válaszmező',
        'referee'                  => 'játékvezető',
        'referees'                 => 'játékvezetők',
        'reject_reason'            => 'elutasítás oka',
        'remember'                 => 'megjegyzés',
        'restored_at'              => 'helyreállítás dátuma',
        'result_text_under_image'  => 'eredmény szövege a kép alatt',
        'role'                     => 'szerep',
        'rule'                     => 'szabály',
        'rules'                    => 'szabályok',
        'second'                   => 'másodperc',
        'sex'                      => 'szex',
        'shipment'                 => 'szállítmány',
        'short_text'               => 'rövid szöveg',
        'size'                     => 'méret',
        'skills'                   => 'készségek',
        'slug'                     => 'meztelen csiga',
        'specialization'           => 'szakosodás',
        'started_at'               => 'kezdés dátuma',
        'state'                    => 'állapot',
        'status'                   => 'állapot',
        'street'                   => 'utca',
        'student'                  => 'diák',
        'subject'                  => 'tantárgy',
        'tag'                      => 'címke',
        'tags'                     => 'címkék',
        'teacher'                  => 'tanár',
        'terms'                    => 'feltételek',
        'test_description'         => 'teszt leírása',
        'test_locale'              => 'teszt területi beállítás',
        'test_name'                => 'teszt neve',
        'text'                     => 'szöveg',
        'time'                     => 'idő',
        'title'                    => 'cím',
        'type'                     => 'típus',
        'updated_at'               => 'frissítve ekkor',
        'user'                     => 'felhasználó',
        'username'                 => 'felhasználónév',
        'value'                    => 'érték',
        'winner'                   => 'winner',
        'work'                     => 'munka',
        'year'                     => 'év',
    ],
];