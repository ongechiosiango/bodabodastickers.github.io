<?php
// Include your database connection file
require_once 'db-conn.php';

// Kenyan counties data with sub-counties, wards, colors, and logos
$kenyanCounties = [
    "Mombasa" => [
        "color" => "#1E90FF", // Dodger Blue
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/Mombasa_County_Government_logo.png/150px-Mombasa_County_Government_logo.png",
        "sub_counties" => [
            "Changamwe" => ["Port Reitz", "Kipevu", "Airport", "Miritini", "Chaani"],
            "Jomvu" => ["Jomvu Kuu", "Mikindani", "Mjambere"],
            "Kisauni" => ["Mjambere", "Junda", "Bamburi", "Mwakirunge", "Mtopanga", "Mikindani"],
            "Likoni" => ["Likoni", "Diani", "Timbwani", "Shika Adabu"],
            "Mvita" => ["Mji wa Kale", "Tudor", "Tononoka", "Shimanzi", "Majengo"]
        ]
    ],
    "Kwale" => [
        "color" => "#228B22", // Forest Green
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Kwale_County_Government_logo.png/150px-Kwale_County_Government_logo.png",
        "sub_counties" => [
            "Kinango" => ["Kinango", "Mackinnon Road", "Chengoni", "Mwavumbo"],
            "Lungalunga" => ["Lungalunga", "Pongwe", "Vanga", "Tsimba Golini"],
            "Matuga" => ["Matuga", "Waa", "Tiwi", "Kubo South"],
            "Msambweni" => ["Msambweni", "Gombato Bongwe", "Ukunda", "Ramisi"]
        ]
    ],
    "Kilifi" => [
        "color" => "#FF4500", // Orange Red
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Kilifi_County_Government_logo.png/150px-Kilifi_County_Government_logo.png",
        "sub_counties" => [
            "Ganze" => ["Ganze", "Bamba", "Jaribuni", "Sokoke"],
            "Kaloleni" => ["Kaloleni", "Kayafungo", "Mariakani", "Mavueni"],
            "Kilifi North" => ["Tezo", "Sokoni", "Kibarani", "Dabaso"],
            "Kilifi South" => ["Shimo la Tewa", "Chasimba", "Mtwapa", "Matsangoni"],
            "Magarini" => ["Magarini", "Marafa", "Gongoni", "Adu"]
        ]
    ],
    "Tana River" => [
        "color" => "#9932CC", // Dark Orchid
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Tana_River_County_Government_logo.png/150px-Tana_River_County_Government_logo.png",
        "sub_counties" => [
            "Bura" => ["Bura", "Dekaharia", "Jarajara", "Fafi"],
            "Galole" => ["Garsen Central", "Garsen North", "Garsen West", "Garsen South"],
            "Garsen" => ["Kipini East", "Kipini West", "Garsen South", "Garsen North"]
        ]
    ],
    "Lamu" => [
        "color" => "#20B2AA", // Light Sea Green
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Lamu_County_Government_logo.png/150px-Lamu_County_Government_logo.png",
        "sub_counties" => [
            "Lamu East" => ["Faza", "Kiunga", "Basuba", "Mkokoni"],
            "Lamu West" => ["Shella", "Mkomani", "Hindi", "Mpeketoni"]
        ]
    ],
    "Taita-Taveta" => [
        "color" => "#FF6347", // Tomato
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Taita-Taveta_County_Government_logo.png/150px-Taita-Taveta_County_Government_logo.png",
        "sub_counties" => [
            "Mwatate" => ["Mwatate", "Bura", "Chawia", "Wusi"],
            "Taveta" => ["Taveta", "Mahoo", "Mata", "Kimorigho"],
            "Voi" => ["Voi", "Mbololo", "Sagala", "Marungu"],
            "Wundanyi" => ["Wundanyi", "Werugha", "Wumingu", "Mbale"]
        ]
    ],
    "Garissa" => [
        "color" => "#4682B4", // Steel Blue
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Garissa_County_Government_logo.png/150px-Garissa_County_Government_logo.png",
        "sub_counties" => [
            "Daadab" => ["Dadaab", "Labisgale", "Damajale", "Liboi"],
            "Fafi" => ["Bura", "Dekaharia", "Jarajara", "Nanighi"],
            "Garissa Township" => ["Garissa Township", "Iftin", "Balambala", "Saka"],
            "Hulugho" => ["Hulugho", "Sangailu", "Ijara", "Masalani"],
            "Lagdera" => ["Modogashe", "Benane", "Goreale", "Sala"]
        ]
    ],
    "Wajir" => [
        "color" => "#2E8B57", // Sea Green
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Wajir_County_Government_logo.png/150px-Wajir_County_Government_logo.png",
        "sub_counties" => [
            "Eldas" => ["Eldas", "Della", "Lakoley South", "Elnur"],
            "Tarbaj" => ["Tarbaj", "Wargadud", "Kutulo", "Elben"],
            "Wajir East" => ["Wajir East", "Bura", "Tarbaj", "Wagalla"],
            "Wajir North" => ["Buna", "Griftu", "Habaswein", "Sarman"],
            "Wajir South" => ["Wajir South", "Griftu", "Khorof Harar", "Lafey"]
        ]
    ],
    "Mandera" => [
        "color" => "#DAA520", // Golden Rod
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Mandera_County_Government_logo.png/150px-Mandera_County_Government_logo.png",
        "sub_counties" => [
            "Banissa" => ["Banissa", "Derkhale", "Guba", "Malkamari"],
            "Lafey" => ["Lafey", "Sala", "Alango", "Warankara"],
            "Mandera East" => ["Mandera East", "Khalalio", "Neboi", "Takaba"],
            "Mandera North" => ["Rhamu", "Rhamu Dimtu", "Elwak", "Lafey"],
            "Mandera West" => ["Lafey", "Wargadud", "Kutulo", "Elben"]
        ]
    ],
    "Marsabit" => [
        "color" => "#CD5C5C", // Indian Red
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Marsabit_County_Government_logo.png/150px-Marsabit_County_Government_logo.png",
        "sub_counties" => [
            "Laisamis" => ["Laisamis", "Logologo", "Korr", "Ngurunit"],
            "Moyale" => ["Moyale", "Sololo", "Butiye", "Girgir"],
            "North Horr" => ["North Horr", "Illeret", "Maikona", "Kalacha"],
            "Saku" => ["Saku", "Sagante", "Karare", "Marsabit Central"]
        ]
    ],
    "Isiolo" => [
        "color" => "#6495ED", // Cornflower Blue
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Isiolo_County_Government_logo.png/150px-Isiolo_County_Government_logo.png",
        "sub_counties" => [
            "Isiolo" => ["Isiolo Central", "Wabera", "Burat", "Ngare Mara"],
            "Garbatulla" => ["Garbatulla", "Kinna", "Sericho", "Merti"],
            "Merti" => ["Merti", "Garba", "Kina", "Sericho"]
        ]
    ],
    "Meru" => [
        "color" => "#4169E1", // Royal Blue
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Meru_County_Government_logo.png/150px-Meru_County_Government_logo.png",
        "sub_counties" => [
            "Buuri" => ["Timau", "Kibirichia", "Ruiri", "Kithirune"],
            "Igembe Central" => ["Maua", "Kangeta", "Athiru Gaiti", "Akachiu"],
            "Igembe North" => ["Laare", "Mitunguu", "Kianjai", "Nkuene"],
            "Igembe South" => ["Antuambui", "Ntunene", "Antubetwe Kiongo", "Naathu"],
            "Imenti North" => ["Mitunguu", "Kianjai", "Nkuene", "Laare"],
            "Imenti South" => ["Mitunguu", "Kianjai", "Nkuene", "Laare"],
            "Tigania East" => ["Mitunguu", "Kianjai", "Nkuene", "Laare"],
            "Tigania West" => ["Mitunguu", "Kianjai", "Nkuene", "Laare"]
        ]
    ],
    "Tharaka-Nithi" => [
        "color" => "#8B4513", // Saddle Brown
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Tharaka-Nithi_County_Government_logo.png/150px-Tharaka-Nithi_County_Government_logo.png",
        "sub_counties" => [
            "Chuka" => ["Chuka", "Iriamurai", "Karingani", "Magumoni"],
            "Maara" => ["Mitheru", "Muthambi", "Mugwe", "Mikinduri"],
            "Tharaka" => ["Tharaka", "Marimanti", "Mugwe", "Mikinduri"]
        ]
    ],
    "Embu" => [
        "color" => "#2F4F4F", // Dark Slate Gray
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Embu_County_Government_logo.png/150px-Embu_County_Government_logo.png",
        "sub_counties" => [
            "Embu East" => ["Runyenjes", "Kithimu", "Ngandori", "Kithimani"],
            "Embu North" => ["Manyatta", "Ruring'u", "Kithimu", "Ngandori"],
            "Embu West" => ["Runyenjes", "Kithimu", "Ngandori", "Kithimani"],
            "Mbeere North" => ["Siakago", "Kanyuambora", "Ngariama", "Kiambere"],
            "Mbeere South" => ["Island", "Kiambere", "Mavuria", "Nthawa"]
        ]
    ],
    "Kitui" => [
        "color" => "#556B2F", // Dark Olive Green
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Kitui_County_Government_logo.png/150px-Kitui_County_Government_logo.png",
        "sub_counties" => [
            "Kitui Central" => ["Kitui Township", "Kwa Mutonga", "Kanyangi", "Mutonguni"],
            "Kitui East" => ["Mutomo", "Ikutha", "Kanziko", "Athi"],
            "Kitui Rural" => ["Kitui Township", "Kwa Mutonga", "Kanyangi", "Mutonguni"],
            "Kitui South" => ["Mutomo", "Ikutha", "Kanziko", "Athi"],
            "Kitui West" => ["Mwingi Central", "Mwingi East", "Mwingi West", "Mwingi North"],
            "Mwingi Central" => ["Mwingi Central", "Mwingi East", "Mwingi West", "Mwingi North"],
            "Mwingi East" => ["Mwingi Central", "Mwingi East", "Mwingi West", "Mwingi North"],
            "Mwingi North" => ["Mwingi Central", "Mwingi East", "Mwingi West", "Mwingi North"],
            "Mwingi West" => ["Mwingi Central", "Mwingi East", "Mwingi West", "Mwingi North"]
        ]
    ],
    "Machakos" => [
        "color" => "#6B8E23", // Olive Drab
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Machakos_County_Government_logo.png/150px-Machakos_County_Government_logo.png",
        "sub_counties" => [
            "Kathiani" => ["Kathiani", "Mitaboni", "Kola", "Mavoloni"],
            "Machakos Town" => ["Machakos Town", "Mumbuni North", "Muvuti", "Kola"],
            "Masinga" => ["Masinga", "Kivaa", "Kithyoko", "Masinga"],
            "Matungulu" => ["Tala", "Matungulu", "Kangundo", "Kathiani"],
            "Mavoko" => ["Athi River", "Kinanie", "Muthwani", "Syokimau"],
            "Mwala" => ["Mwala", "Masii", "Muthetheni", "Mbiuni"],
            "Yatta" => ["Yatta", "Kithimani", "Kanyangi", "Matuu"]
        ]
    ],
    "Makueni" => [
        "color" => "#B8860B", // Dark Golden Rod
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Makueni_County_Government_logo.png/150px-Makueni_County_Government_logo.png",
        "sub_counties" => [
            "Kaiti" => ["Kaiti", "Kikumini", "Kithuki", "Mbitini"],
            "Kibwezi East" => ["Kibwezi", "Makindu", "Nguu", "Masumba"],
            "Kibwezi West" => ["Kibwezi", "Makindu", "Nguu", "Masumba"],
            "Kilome" => ["Kilome", "Kithuki", "Mbitini", "Kaiti"],
            "Makueni" => ["Wote", "Kathonzweni", "Kaiti", "Kilungu"],
            "Mbooni" => ["Mbooni", "Kithungo", "Kiteta", "Waia"],
            "Nguu" => ["Nguu", "Masumba", "Kibwezi", "Makindu"]
        ]
    ],
    "Nyandarua" => [
        "color" => "#D2691E", // Chocolate
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Nyandarua_County_Government_logo.png/150px-Nyandarua_County_Government_logo.png",
        "sub_counties" => [
            "Kinangop" => ["Kinangop", "Kipipiri", "Githabai", "Magumu"],
            "Kipipiri" => ["Kipipiri", "Githabai", "Magumu", "Kinangop"],
            "Ndaragwa" => ["Ndaragwa", "Leshau", "Kiriita", "Rurii"],
            "Ol Kalou" => ["Ol Kalou", "Kinangop", "Kipipiri", "Githabai"],
            "Ol Jorok" => ["Ol Jorok", "Kipipiri", "Githabai", "Magumu"]
        ]
    ],
    "Nyeri" => [
        "color" => "#8B008B", // Dark Magenta
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Nyeri_County_Government_logo.png/150px-Nyeri_County_Government_logo.png",
        "sub_counties" => [
            "Kieni East" => ["Kieni East", "Kieni West", "Mathira East", "Mathira West"],
            "Kieni West" => ["Kieni East", "Kieni West", "Mathira East", "Mathira West"],
            "Mathira East" => ["Kieni East", "Kieni West", "Mathira East", "Mathira West"],
            "Mathira West" => ["Kieni East", "Kieni West", "Mathira East", "Mathira West"],
            "Mukurweini" => ["Mukurweini", "Tetu", "Othaya", "Nyeri Town"],
            "Nyeri Central" => ["Nyeri Central", "Nyeri South", "Nyeri Town", "Kiganjo"],
            "Tetu" => ["Tetu", "Othaya", "Nyeri Town", "Kiganjo"]
        ]
    ],
    "Kirinyaga" => [
        "color" => "#483D8B", // Dark Slate Blue
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Kirinyaga_County_Government_logo.png/150px-Kirinyaga_County_Government_logo.png",
        "sub_counties" => [
            "Gichugu" => ["Gichugu", "Kianyaga", "Baragwi", "Ndia"],
            "Kirinyaga Central" => ["Kerugoya", "Inoi", "Mutithi", "Kanyekini"],
            "Kirinyaga East" => ["Kianyaga", "Baragwi", "Ndia", "Gichugu"],
            "Kirinyaga West" => ["Kerugoya", "Inoi", "Mutithi", "Kanyekini"],
            "Mwea East" => ["Mwea", "Thiba", "Wamumu", "Murinduko"],
            "Mwea West" => ["Mwea", "Thiba", "Wamumu", "Murinduko"]
        ]
    ],
    "Murang'a" => [
        "color" => "#2E8B57", // Sea Green
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Murang%27a_County_Government_logo.png/150px-Murang%27a_County_Government_logo.png",
        "sub_counties" => [
            "Gatanga" => ["Gatanga", "Kihumbuini", "Kariara", "Kigumo"],
            "Kahuro" => ["Kahuro", "Muthithi", "Kigumo", "Kangari"],
            "Kandara" => ["Kandara", "Gatanga", "Kihumbuini", "Kariara"],
            "Kangema" => ["Kangema", "Kihumbuini", "Kariara", "Kigumo"],
            "Kigumo" => ["Kigumo", "Kangari", "Kahuro", "Muthithi"],
            "Maragwa" => ["Maragwa", "Kamahuha", "Kambiti", "Makuyu"],
            "Mathioya" => ["Mathioya", "Kihumbuini", "Kariara", "Kigumo"]
        ]
    ],
    "Kiambu" => [
        "color" => "#8B4513", // Saddle Brown
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Kiambu_County_Government_logo.png/150px-Kiambu_County_Government_logo.png",
        "sub_counties" => [
            "Gatundu North" => ["Gatundu North", "Gatundu South", "Githunguri", "Juja"],
            "Gatundu South" => ["Gatundu South", "Githunguri", "Juja", "Thika"],
            "Githunguri" => ["Githunguri", "Juja", "Thika", "Ruiru"],
            "Juja" => ["Juja", "Thika", "Ruiru", "Gatundu North"],
            "Kabete" => ["Kabete", "Kikuyu", "Kiambaa", "Limuru"],
            "Kiambaa" => ["Kiambaa", "Limuru", "Kikuyu", "Kabete"],
            "Kiambu" => ["Kiambu", "Kikuyu", "Kiambaa", "Limuru"],
            "Kikuyu" => ["Kikuyu", "Kiambaa", "Limuru", "Kabete"],
            "Limuru" => ["Limuru", "Kikuyu", "Kiambaa", "Kabete"],
            "Ruiru" => ["Ruiru", "Juja", "Thika", "Gatundu North"],
            "Thika" => ["Thika", "Ruiru", "Juja", "Gatundu North"]
        ]
    ],
    "Turkana" => [
        "color" => "#CD853F", // Peru
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Turkana_County_Government_logo.png/150px-Turkana_County_Government_logo.png",
        "sub_counties" => [
            "Kibish" => ["Kibish", "Lokichogio", "Kakuma", "Lokitaung"],
            "Loima" => ["Loima", "Lokiriama", "Kotaruk", "Lobei"],
            "Turkana Central" => ["Lodwar", "Kanamkemer", "Kalokol", "Kangatotha"],
            "Turkana East" => ["Lokori", "Kochodin", "Kalapata", "Kapedo"],
            "Turkana North" => ["Lokitaung", "Kakuma", "Lokichogio", "Kibish"],
            "Turkana South" => ["Lokichar", "Katilu", "Kalapata", "Kapedo"],
            "Turkana West" => ["Kakuma", "Lokichogio", "Lokitaung", "Kibish"]
        ]
    ],
    "West Pokot" => [
        "color" => "#A0522D", // Sienna
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/West_Pokot_County_Government_logo.png/150px-West_Pokot_County_Government_logo.png",
        "sub_counties" => [
            "Central Pokot" => ["Kapenguria", "Mnagei", "Sook", "Kacheliba"],
            "North Pokot" => ["Kacheliba", "Sook", "Mnagei", "Kapenguria"],
            "Pokot South" => ["Chepareria", "Lelan", "Sook", "Kacheliba"],
            "West Pokot" => ["Kapenguria", "Mnagei", "Sook", "Kacheliba"]
        ]
    ],
    "Samburu" => [
        "color" => "#D2691E", // Chocolate
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Samburu_County_Government_logo.png/150px-Samburu_County_Government_logo.png",
        "sub_counties" => [
            "Samburu East" => ["Wamba", "Waso", "Archers Post", "Baragoi"],
            "Samburu North" => ["Baragoi", "South Horr", "Ndoto", "Nyiro"],
            "Samburu West" => ["Maralal", "Suguta Marmar", "Loosuk", "Poro"]
        ]
    ],
    "Trans Nzoia" => [
        "color" => "#B8860B", // Dark Golden Rod
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Trans_Nzoia_County_Government_logo.png/150px-Trans_Nzoia_County_Government_logo.png",
        "sub_counties" => [
            "Cherangany" => ["Cherangany", "Sinyerere", "Chepsiro", "Kinyoro"],
            "Endebess" => ["Endebess", "Matumbei", "Kinyoro", "Chepsiro"],
            "Kiminini" => ["Kiminini", "Waitaluk", "Sirende", "Hospital"],
            "Kwanza" => ["Kwanza", "Bidii", "Matisi", "Tuwani"],
            "Saboti" => ["Saboti", "Machewa", "Kinyoro", "Chepsiro"]
        ]
    ],
    "Uasin Gishu" => [
        "color" => "#DAA520", // Golden Rod
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Uasin_Gishu_County_Government_logo.png/150px-Uasin_Gishu_County_Government_logo.png",
        "sub_counties" => [
            "Ainabkoi" => ["Ainabkoi", "Kapsoya", "Kimumu", "Kesses"],
            "Kapseret" => ["Kapseret", "Kipkenyo", "Langas", "Megun"],
            "Kesses" => ["Kesses", "Kimumu", "Kapsoya", "Ainabkoi"],
            "Moiben" => ["Moiben", "Kimumu", "Kapsoya", "Ainabkoi"],
            "Soy" => ["Soy", "Kuinet", "Kipsomba", "Ziwa"],
            "Turbo" => ["Turbo", "Kamagut", "Kapsabet", "Kipkaren"]
        ]
    ],
    "Elgeyo-Marakwet" => [
        "color" => "#CD5C5C", // Indian Red
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Elgeyo-Marakwet_County_Government_logo.png/150px-Elgeyo-Marakwet_County_Government_logo.png",
        "sub_counties" => [
            "Keiyo North" => ["Iten", "Tambach", "Chepkorio", "Metkei"],
            "Keiyo South" => ["Chepkorio", "Metkei", "Iten", "Tambach"],
            "Marakwet East" => ["Kapcherop", "Cheptongei", "Kapsowar", "Arror"],
            "Marakwet West" => ["Kapcherop", "Cheptongei", "Kapsowar", "Arror"]
        ]
    ],
    "Nandi" => [
        "color" => "#9932CC", // Dark Orchid
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Nandi_County_Government_logo.png/150px-Nandi_County_Government_logo.png",
        "sub_counties" => [
            "Aldai" => ["Aldai", "Kabiyet", "Kobujoi", "Kaptumo"],
            "Chesumei" => ["Chesumei", "Chemundu", "Kapsabet", "Kilibwoni"],
            "Emgwen" => ["Emgwen", "Kilibwoni", "Kapsabet", "Chemundu"],
            "Mosop" => ["Mosop", "Kapsabet", "Kilibwoni", "Chemundu"],
            "Nandi Hills" => ["Nandi Hills", "Kapsabet", "Kilibwoni", "Chemundu"],
            "Tinderet" => ["Tinderet", "Kapsabet", "Kilibwoni", "Chemundu"]
        ]
    ],
    "Baringo" => [
        "color" => "#8B0000", // Dark Red
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Baringo_County_Government_logo.png/150px-Baringo_County_Government_logo.png",
        "sub_counties" => [
            "Baringo Central" => ["Kabarnet", "Sacho", "Bartabwa", "Kabartonjo"],
            "Baringo North" => ["Bartabwa", "Kabartonjo", "Kabarnet", "Sacho"],
            "Baringo South" => ["Marigat", "Mochongoi", "Mukutani", "Emining"],
            "Eldama Ravine" => ["Eldama Ravine", "Mogotio", "Emining", "Mukutani"],
            "Mogotio" => ["Mogotio", "Emining", "Mukutani", "Marigat"],
            "Tiaty" => ["Tiaty", "Churo", "Amaya", "Tangulbei"]
        ]
    ],
    "Laikipia" => [
        "color" => "#483D8B", // Dark Slate Blue
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Laikipia_County_Government_logo.png/150px-Laikipia_County_Government_logo.png",
        "sub_counties" => [
            "Laikipia Central" => ["Nanyuki", "Rumuruti", "Ol Moran", "Ngobit"],
            "Laikipia East" => ["Nanyuki", "Rumuruti", "Ol Moran", "Ngobit"],
            "Laikipia North" => ["Rumuruti", "Ol Moran", "Ngobit", "Nanyuki"],
            "Laikipia West" => ["Rumuruti", "Ol Moran", "Ngobit", "Nanyuki"],
            "Nyahururu" => ["Nyahururu", "Rumuruti", "Ol Moran", "Ngobit"]
        ]
    ],
    "Nakuru" => [
        "color" => "#2E8B57", // Sea Green
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Nakuru_County_Government_logo.png/150px-Nakuru_County_Government_logo.png",
        "sub_counties" => [
            "Bahati" => ["Bahati", "Dundori", "Kabatini", "Kiamaina"],
            "Gilgil" => ["Gilgil", "Elementaita", "Mbaruk", "Malewa"],
            "Kuresoi North" => ["Kuresoi", "Molo", "Elburgon", "Mariashoni"],
            "Kuresoi South" => ["Kuresoi", "Molo", "Elburgon", "Mariashoni"],
            "Molo" => ["Molo", "Elburgon", "Mariashoni", "Kuresoi"],
            "Naivasha" => ["Naivasha", "Mai Mahiu", "Biashara", "Kihoto"],
            "Nakuru East" => ["Nakuru East", "Nakuru West", "Barut", "London"],
            "Nakuru North" => ["Nakuru North", "Bahati", "Dundori", "Kabatini"],
            "Nakuru West" => ["Nakuru West", "Barut", "London", "Nakuru East"],
            "Njoro" => ["Njoro", "Mau Narok", "Mauche", "Lare"],
            "Rongai" => ["Rongai", "Menengai", "Solai", "Visoi"],
            "Subukia" => ["Subukia", "Waseges", "Kabazi", "Mariashoni"]
        ]
    ],
    "Narok" => [
        "color" => "#8B4513", // Saddle Brown
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Narok_County_Government_logo.png/150px-Narok_County_Government_logo.png",
        "sub_counties" => [
            "Narok East" => ["Narok East", "Ololulunga", "Nkareta", "Olorropil"],
            "Narok North" => ["Narok North", "Nkareta", "Olorropil", "Ololulunga"],
            "Narok South" => ["Narok South", "Nkareta", "Olorropil", "Ololulunga"],
            "Narok West" => ["Narok West", "Nkareta", "Olorropil", "Ololulunga"],
            "Trans Mara East" => ["Kilgoris", "Lolgorien", "Nkareta", "Olorropil"],
            "Trans Mara West" => ["Lolgorien", "Nkareta", "Olorropil", "Kilgoris"]
        ]
    ],
    "Kajiado" => [
        "color" => "#556B2F", // Dark Olive Green
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Kajiado_County_Government_logo.png/150px-Kajiado_County_Government_logo.png",
        "sub_counties" => [
            "Isinya" => ["Isinya", "Kitengela", "Oloosirkon", "Kiserian"],
            "Kajiado Central" => ["Kajiado", "Oloosirkon", "Kiserian", "Isinya"],
            "Kajiado East" => ["Kajiado East", "Oloosirkon", "Kiserian", "Isinya"],
            "Kajiado North" => ["Kajiado North", "Kitengela", "Oloosirkon", "Kiserian"],
            "Kajiado West" => ["Kajiado West", "Oloosirkon", "Kiserian", "Isinya"],
            "Loitokitok" => ["Loitokitok", "Kimana", "Oloosirkon", "Kiserian"],
            "Mashuuru" => ["Mashuuru", "Oloosirkon", "Kiserian", "Isinya"]
        ]
    ],
    "Kericho" => [
        "color" => "#006400", // Dark Green
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Kericho_County_Government_logo.png/150px-Kericho_County_Government_logo.png",
        "sub_counties" => [
            "Ainamoi" => ["Ainamoi", "Kapsoit", "Kipkelion", "Kedowa"],
            "Belgut" => ["Belgut", "Kipkelion", "Kedowa", "Kapsoit"],
            "Bureti" => ["Bureti", "Kipkelion", "Kedowa", "Kapsoit"],
            "Kipkelion East" => ["Kipkelion East", "Kipkelion", "Kedowa", "Kapsoit"],
            "Kipkelion West" => ["Kipkelion West", "Kipkelion", "Kedowa", "Kapsoit"],
            "Soin Sigowet" => ["Soin Sigowet", "Kipkelion", "Kedowa", "Kapsoit"]
        ]
    ],
    "Bomet" => [
        "color" => "#2F4F4F", // Dark Slate Gray
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Bomet_County_Government_logo.png/150px-Bomet_County_Government_logo.png",
        "sub_counties" => [
            "Bomet Central" => ["Bomet Central", "Chepalungu", "Sotik", "Konoin"],
            "Bomet East" => ["Bomet East", "Chepalungu", "Sotik", "Konoin"],
            "Chepalungu" => ["Chepalungu", "Sotik", "Konoin", "Bomet Central"],
            "Konoin" => ["Konoin", "Chepalungu", "Sotik", "Bomet Central"],
            "Sotik" => ["Sotik", "Chepalungu", "Konoin", "Bomet Central"]
        ]
    ],
    "Kakamega" => [
        "color" => "#8B008B", // Dark Magenta
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Kakamega_County_Government_logo.png/150px-Kakamega_County_Government_logo.png",
        "sub_counties" => [
            "Butere" => ["Butere", "Khwisero", "Mumias", "Matungu"],
            "Kakamega Central" => ["Kakamega Central", "Lurambi", "Navakholo", "Likuyani"],
            "Khwisero" => ["Khwisero", "Butere", "Mumias", "Matungu"],
            "Likuyani" => ["Likuyani", "Navakholo", "Lurambi", "Kakamega Central"],
            "Lugari" => ["Lugari", "Likuyani", "Navakholo", "Lurambi"],
            "Lurambi" => ["Lurambi", "Kakamega Central", "Navakholo", "Likuyani"],
            "Matungu" => ["Matungu", "Khwisero", "Butere", "Mumias"],
            "Mumias East" => ["Mumias East", "Matungu", "Khwisero", "Butere"],
            "Mumias West" => ["Mumias West", "Matungu", "Khwisero", "Butere"],
            "Navakholo" => ["Navakholo", "Lurambi", "Kakamega Central", "Likuyani"],
            "Shinyalu" => ["Shinyalu", "Lurambi", "Kakamega Central", "Navakholo"]
        ]
    ],
    "Vihiga" => [
        "color" => "#483D8B", // Dark Slate Blue
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Vihiga_County_Government_logo.png/150px-Vihiga_County_Government_logo.png",
        "sub_counties" => [
            "Emuhaya" => ["Emuhaya", "Luanda", "Wodanga", "Sabatia"],
            "Hamisi" => ["Hamisi", "Luanda", "Wodanga", "Sabatia"],
            "Luanda" => ["Luanda", "Wodanga", "Sabatia", "Emuhaya"],
            "Sabatia" => ["Sabatia", "Luanda", "Wodanga", "Emuhaya"],
            "Vihiga" => ["Vihiga", "Luanda", "Wodanga", "Sabatia"]
        ]
    ],
    "Bungoma" => [
        "color" => "#2E8B57", // Sea Green
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Bungoma_County_Government_logo.png/150px-Bungoma_County_Government_logo.png",
        "sub_counties" => [
            "Bumula" => ["Bumula", "Kanduyi", "Webuye", "Kimilili"],
            "Kabuchai" => ["Kabuchai", "Kanduyi", "Webuye", "Kimilili"],
            "Kanduyi" => ["Kanduyi", "Webuye", "Kimilili", "Bumula"],
            "Kimilili" => ["Kimilili", "Kanduyi", "Webuye", "Bumula"],
            "Mt Elgon" => ["Mt Elgon", "Kanduyi", "Webuye", "Kimilili"],
            "Sirisia" => ["Sirisia", "Kanduyi", "Webuye", "Kimilili"],
            "Tongaren" => ["Tongaren", "Kanduyi", "Webuye", "Kimilili"],
            "Webuye East" => ["Webuye East", "Kanduyi", "Webuye", "Kimilili"],
            "Webuye West" => ["Webuye West", "Kanduyi", "Webuye", "Kimilili"]
        ]
    ],
    "Busia" => [
        "color" => "#8B4513", // Saddle Brown
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Busia_County_Government_logo.png/150px-Busia_County_Government_logo.png",
        "sub_counties" => [
            "Budalangi" => ["Budalangi", "Funyula", "Nambale", "Butula"],
            "Butula" => ["Butula", "Funyula", "Nambale", "Budalangi"],
            "Funyula" => ["Funyula", "Nambale", "Butula", "Budalangi"],
            "Nambale" => ["Nambale", "Funyula", "Butula", "Budalangi"],
            "Teso North" => ["Teso North", "Malaba", "Amukura", "Angurai"],
            "Teso South" => ["Teso South", "Malaba", "Amukura", "Angurai"]
        ]
    ],
    "Siaya" => [
        "color" => "#556B2F", // Dark Olive Green
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Siaya_County_Government_logo.png/150px-Siaya_County_Government_logo.png",
        "sub_counties" => [
            "Alego Usonga" => ["Alego Usonga", "Ugunja", "Ugenya", "Gem"],
            "Bondo" => ["Bondo", "Rarieda", "Ugunja", "Ugenya"],
            "Gem" => ["Gem", "Ugunja", "Ugenya", "Alego Usonga"],
            "Rarieda" => ["Rarieda", "Bondo", "Ugunja", "Ugenya"],
            "Ugenya" => ["Ugenya", "Ugunja", "Gem", "Alego Usonga"],
            "Ugunja" => ["Ugunja", "Ugenya", "Gem", "Alego Usonga"]
        ]
    ],
    "Kisumu" => [
        "color" => "#006400", // Dark Green
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Kisumu_County_Government_logo.png/150px-Kisumu_County_Government_logo.png",
        "sub_counties" => [
            "Kisumu Central" => ["Kisumu Central", "Kisumu East", "Kisumu West", "Seme"],
            "Kisumu East" => ["Kisumu East", "Kisumu West", "Seme", "Kisumu Central"],
            "Kisumu West" => ["Kisumu West", "Seme", "Kisumu Central", "Kisumu East"],
            "Muhoroni" => ["Muhoroni", "Nyakach", "Nyando", "Kisumu Central"],
            "Nyakach" => ["Nyakach", "Nyando", "Muhoroni", "Kisumu Central"],
            "Nyando" => ["Nyando", "Muhoroni", "Nyakach", "Kisumu Central"],
            "Seme" => ["Seme", "Kisumu Central", "Kisumu East", "Kisumu West"]
        ]
    ],
    "Homa Bay" => [
        "color" => "#2F4F4F", // Dark Slate Gray
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Homa_Bay_County_Government_logo.png/150px-Homa_Bay_County_Government_logo.png",
        "sub_counties" => [
            "Homa Bay Town" => ["Homa Bay Town", "Rangwe", "Ndhiwa", "Mbita"],
            "Kabondo Kasipul" => ["Kabondo Kasipul", "Rangwe", "Ndhiwa", "Mbita"],
            "Karachuonyo" => ["Karachuonyo", "Rangwe", "Ndhiwa", "Mbita"],
            "Kasipul" => ["Kasipul", "Rangwe", "Ndhiwa", "Mbita"],
            "Mbita" => ["Mbita", "Rangwe", "Ndhiwa", "Homa Bay Town"],
            "Ndhiwa" => ["Ndhiwa", "Rangwe", "Homa Bay Town", "Mbita"],
            "Rangwe" => ["Rangwe", "Ndhiwa", "Homa Bay Town", "Mbita"],
            "Suba" => ["Suba", "Rangwe", "Ndhiwa", "Mbita"]
        ]
    ],
    "Migori" => [
        "color" => "#8B008B", // Dark Magenta
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Migori_County_Government_logo.png/150px-Migori_County_Government_logo.png",
        "sub_counties" => [
            "Awendo" => ["Awendo", "Rongo", "Uriri", "Nyatike"],
            "Kuria East" => ["Kuria East", "Kuria West", "Rongo", "Uriri"],
            "Kuria West" => ["Kuria West", "Kuria East", "Rongo", "Uriri"],
            "Nyatike" => ["Nyatike", "Uriri", "Rongo", "Awendo"],
            "Rongo" => ["Rongo", "Uriri", "Nyatike", "Awendo"],
            "Suna East" => ["Suna East", "Suna West", "Rongo", "Uriri"],
            "Suna West" => ["Suna West", "Suna East", "Rongo", "Uriri"],
            "Uriri" => ["Uriri", "Rongo", "Nyatike", "Awendo"]
        ]
    ],
    "Kisii" => [
        "color" => "#483D8B", // Dark Slate Blue
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Kisii_County_Government_logo.png/150px-Kisii_County_Government_logo.png",
        "sub_counties" => [
            "Bobasi" => ["Bobasi", "Gucha", "Nyaribari", "Kitutu"],
            "Bomachoge Borabu" => ["Bomachoge Borabu", "Bomachoge Chache", "Nyaribari", "Kitutu"],
            "Bomachoge Chache" => ["Bomachoge Chache", "Bomachoge Borabu", "Nyaribari", "Kitutu"],
            "Bonchari" => ["Bonchari", "Nyaribari", "Kitutu", "Bobasi"],
            "Kitutu Chache North" => ["Kitutu Chache North", "Kitutu Chache South", "Nyaribari", "Bobasi"],
            "Kitutu Chache South" => ["Kitutu Chache South", "Kitutu Chache North", "Nyaribari", "Bobasi"],
            "Nyaribari Chache" => ["Nyaribari Chache", "Nyaribari Masaba", "Kitutu", "Bobasi"],
            "Nyaribari Masaba" => ["Nyaribari Masaba", "Nyaribari Chache", "Kitutu", "Bobasi"],
            "South Mugirango" => ["South Mugirango", "Nyaribari", "Kitutu", "Bobasi"]
        ]
    ],
    "Nyamira" => [
        "color" => "#2E8B57", // Sea Green
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Nyamira_County_Government_logo.png/150px-Nyamira_County_Government_logo.png",
        "sub_counties" => [
            "Borabu" => ["Borabu", "Boochi", "Moticho", "Getenga"],
            "Manga" => ["Manga", "Gesima", "Rigoma", "Nyamaiya"],
            "Masaba North" => ["Kemera", "Magombo", "Manga", "Gesima"],
            "Nyamira North" => ["Bonyamatuta", "Township", "Itibo", "Bomwagamo", "Ekerenyo"],
            "Nyamira South" => ["Bokimonge", "Bosamaro", "Bomorenda", "Rigoma"]
        ]
    ],
    "Nairobi" => [
        "color" => "#FF4500", // Orange Red
        "logo" => "https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Nairobi_County_Government_logo.png/150px-Nairobi_County_Government_logo.png",
        "sub_counties" => [
            "Dagoretti North" => ["Kilimani", "Kawangware", "Gatina", "Kileleshwa", "Kabiro"],
            "Dagoretti South" => ["Mutu-ini", "Ngando", "Riruta", "Uthiru", "Waithaka"],
            "Embakasi Central" => ["Kayole North", "Kayole South", "Komarock", "Matopeni"],
            "Embakasi East" => ["Upper Savanna", "Lower Savanna", "Embakasi", "Utawala"],
            "Embakasi North" => ["Kariobangi North", "Dandora Area I", "Dandora Area II", "Dandora Area III"],
            "Embakasi South" => ["Imara Daima", "Kwa Njenga", "Kwa Reuben", "Pipeline"],
            "Embakasi West" => ["Umoja I", "Umoja II", "Mowlem", "Kariobangi South"],
            "Kamukunji" => ["Pumwani", "Eastleigh North", "Eastleigh South", "Airbase"],
            "Kasarani" => ["Clay City", "Mwiki", "Kasarani", "Njiru"],
            "Kibra" => ["Laini Saba", "Lindi", "Makina", "Woodley"],
            "Lang'ata" => ["Karen", "Nairobi West", "Mugumo-ini", "South C"],
            "Makadara" => ["Maringo", "Hamza", "Viwandani", "Harambee"],
            "Mathare" => ["Hospital", "Mabatini", "Huruma", "Ngei"],
            "Roysambu" => ["Githurai", "Kahawa West", "Zimmerman", "Roysambu"],
            "Ruaraka" => ["Baba Dogo", "Utalii", "Mathare North", "Lucky Summer"],
            "Starehe" => ["Nairobi Central", "Ngara", "Pangani", "Landimawe"],
            "Westlands" => ["Kitisuru", "Parklands", "Karura", "Kangemi"]
        ]
    ]
];

// Function to generate sticker number
function generateStickerNumber($county) {
    global $conn;
    
    $result = $conn->query("SELECT MAX(sticker_id) as max_id FROM stickers");
    if (!$result) {
        die("Error getting next sticker number: " . $conn->error);
    }
    $row = $result->fetch_assoc();
    $next_id = ($row['max_id'] ?? 0) + 1;
    
    $number = str_pad($next_id, 5, '0', STR_PAD_LEFT);
    $county_abbr = strtoupper(substr($county, 0, 1));
    
    return "CGNMS/{$county_abbr}{$number}";
}

// Process AJAX requests
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    if ($_GET['action'] == 'get_subcounties' && isset($_GET['county'])) {
        $county = $_GET['county'];
        if (isset($kenyanCounties[$county]['sub_counties'])) {
            echo json_encode(array_keys($kenyanCounties[$county]['sub_counties']));
        } else {
            echo json_encode([]);
        }
        exit();
    }
    
    if ($_GET['action'] == 'get_wards' && isset($_GET['county']) && isset($_GET['subcounty'])) {
        $county = $_GET['county'];
        $subcounty = $_GET['subcounty'];
        if (isset($kenyanCounties[$county]['sub_counties'][$subcounty])) {
            echo json_encode($kenyanCounties[$county]['sub_counties'][$subcounty]);
        } else {
            echo json_encode([]);
        }
        exit();
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save'])) {
        $county = $conn->real_escape_string($_POST['county']);
        $commencing_date = $conn->real_escape_string($_POST['commencing_date']);
        $expiry_period = (int)$_POST['expiry_period'];
        $registration_no = $conn->real_escape_string($_POST['registration_no']);
        $sub_county = $conn->real_escape_string($_POST['sub_county']);
        $ward = $conn->real_escape_string($_POST['ward']);
        
        $sticker_number = generateStickerNumber($county);
        $expiry_date = date('Y-m-d', strtotime($commencing_date . " + $expiry_period months"));
        
        $sql = "INSERT INTO stickers (sticker_number, county, commencing_date, expiry_date, 
                registration_no, sub_county, ward) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("sssssss", $sticker_number, $county, $commencing_date, $expiry_date,
                         $registration_no, $sub_county, $ward);
        
        if ($stmt->execute()) {
            header("Location: sticker-print.php?print_id=" . $stmt->insert_id);
            exit();
        } else {
            $error = "Error saving sticker: " . $stmt->error;
        }
    }
}

// Get saved data for printing
$sticker_data = null;
if (isset($_GET['print_id'])) {
    $print_id = (int)$_GET['print_id'];
    $stmt = $conn->prepare("SELECT * FROM stickers WHERE sticker_id = ?");
    $stmt->bind_param("i", $print_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $sticker_data = $result->fetch_assoc();
    
    if (!$sticker_data) {
        $error = "Sticker not found!";
    }
    
    // Get county info
    $county_info = $kenyanCounties[$sticker_data['county']] ?? 
        ['color' => '#4e73df', 'logo' => ''];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motorcycle Sticker Printing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border: none;
        }
        .card-header {
            background-color: #4e73df;
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }
        .sticker {
            border: 2px solid #000;
            padding: 30px;
            margin: 30px auto;
            width: 350px;
            background-color: white;
            position: relative;
        }
        .sticker-header {
            background-color: <?php echo $county_info['color'] ?? '#4e73df'; ?>;
            color: white;
            padding: 10px;
            margin: -30px -30px 20px -30px;
            text-align: center;
            font-weight: bold;
        }
        .sticker-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 25px;
        }
        .sticker-detail {
            margin: 15px 0;
            padding-left: 15px;
            border-left: 3px solid <?php echo $county_info['color'] ?? '#4e73df'; ?>;
        }
        .county-logo {
            width: 60px;
            height: 60px;
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .qr-code {
            width: 80px;
            height: 80px;
            margin: 10px auto;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            .sticker, .sticker * {
                visibility: visible;
            }
            .sticker {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="mb-0">Motorcycle Sticker Printing System</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        
                        <?php if (!$sticker_data): ?>
                        <form method="post" action="sticker-print.php" id="stickerForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-primary text-white">
                                            Motorcycle Information
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label">County</label>
                                                <select class="form-select" id="county" name="county" required>
                                                    <option value="">Select County</option>
                                                    <?php foreach (array_keys($kenyanCounties) as $county): ?>
                                                        <option value="<?= htmlspecialchars($county) ?>"><?= htmlspecialchars($county) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Sub-County</label>
                                                <select class="form-select" id="sub_county" name="sub_county" required disabled>
                                                    <option value="">Select Sub-County</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Ward</label>
                                                <select class="form-select" id="ward" name="ward" required disabled>
                                                    <option value="">Select Ward</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Registration Number</label>
                                                <input type="text" class="form-control" name="registration_no" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-primary text-white">
                                            Sticker Details
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label">Commencing Date</label>
                                                <input type="date" class="form-control" name="commencing_date" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Expiry Period</label>
                                                <select class="form-select" name="expiry_period" required>
                                                    <option value="1">1 Month</option>
                                                    <option value="3">3 Months</option>
                                                    <option value="6">6 Months</option>
                                                    <option value="12">1 Year</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="save" class="btn btn-primary btn-lg">
                                    Save & Generate Sticker
                                </button>
                            </div>
                        </form>
                        <?php else: ?>
                        <div class="text-center">
                            <div class="sticker">
                                <?php if (!empty($county_info['logo'])): ?>
                                <img src="<?= htmlspecialchars($county_info['logo']) ?>" class="county-logo">
                                <?php endif; ?>
                                
                                <div class="sticker-header">
                                    COUNTY GOVERNMENT OF <?= strtoupper(htmlspecialchars($sticker_data['county'])) ?>
                                </div>
                                
                                <div class="sticker-title">MOTORCYCLE STICKER</div>
                                
                                <div id="qr-code" class="qr-code"></div>
                                
                                <div class="sticker-detail">
                                    <strong>Commencing Date:</strong> <?= date('d/m/Y', strtotime($sticker_data['commencing_date'])) ?>
                                </div>
                                
                                <div class="sticker-detail">
                                    <strong>EXPIRING:</strong> <?= date('d/m/Y', strtotime($sticker_data['expiry_date'])) ?>
                                </div>
                                
                                <div class="sticker-detail">
                                    <strong>REGISTRATION NO:</strong> <?= htmlspecialchars($sticker_data['registration_no']) ?>
                                </div>
                                
                                <div class="sticker-detail">
                                    <strong>SUB-COUNTY:</strong> <?= htmlspecialchars($sticker_data['sub_county']) ?>
                                </div>
                                
                                <?php if (!empty($sticker_data['ward'])): ?>
                                <div class="sticker-detail">
                                    <strong>WARD:</strong> <?= htmlspecialchars($sticker_data['ward']) ?>
                                </div>
                                <?php endif; ?>
                                
                                <div class="sticker-detail">
                                    <strong>STICKER NO:</strong> <?= htmlspecialchars($sticker_data['sticker_number']) ?>
                                </div>
                            </div>
                            
                            <div class="mt-4 no-print">
                                <button onclick="window.print()" class="btn btn-primary me-3">
                                    Print Sticker
                                </button>
                                <a href="sticker-print.php" class="btn btn-secondary">
                                    Create New Sticker
                                </a>
                            </div>
                        </div>
                        
                        <script>
                            new QRCode(document.getElementById("qr-code"), {
                                text: "Sticker: <?= $sticker_data['sticker_number'] ?>\nReg: <?= $sticker_data['registration_no'] ?>\nCounty: <?= $sticker_data['county'] ?>",
                                width: 80,
                                height: 80
                            });
                            
                            window.onload = function() {
                                setTimeout(function() {
                                    window.print();
                                }, 500);
                            };
                        </script>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const countySelect = document.getElementById('county');
            const subCountySelect = document.getElementById('sub_county');
            const wardSelect = document.getElementById('ward');
            
            countySelect.addEventListener('change', function() {
                const county = this.value;
                subCountySelect.innerHTML = '<option value="">Select Sub-County</option>';
                wardSelect.innerHTML = '<option value="">Select Ward</option>';
                
                if (county) {
                    fetch(`sticker-print.php?action=get_subcounties&county=${encodeURIComponent(county)}`)
                        .then(response => response.json())
                        .then(subCounties => {
                            subCounties.forEach(subCounty => {
                                const option = document.createElement('option');
                                option.value = subCounty;
                                option.textContent = subCounty;
                                subCountySelect.appendChild(option);
                            });
                            subCountySelect.disabled = false;
                        });
                } else {
                    subCountySelect.disabled = true;
                    wardSelect.disabled = true;
                }
            });
            
            subCountySelect.addEventListener('change', function() {
                const county = countySelect.value;
                const subCounty = this.value;
                wardSelect.innerHTML = '<option value="">Select Ward</option>';
                
                if (county && subCounty) {
                    fetch(`sticker-print.php?action=get_wards&county=${encodeURIComponent(county)}&subcounty=${encodeURIComponent(subCounty)}`)
                        .then(response => response.json())
                        .then(wards => {
                            wards.forEach(ward => {
                                const option = document.createElement('option');
                                option.value = ward;
                                option.textContent = ward;
                                wardSelect.appendChild(option);
                            });
                            wardSelect.disabled = false;
                        });
                } else {
                    wardSelect.disabled = true;
                }
            });
        });
    </script>
</body>
</html>