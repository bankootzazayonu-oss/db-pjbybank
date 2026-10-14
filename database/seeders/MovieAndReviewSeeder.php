<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;
use App\Models\Activity;
use App\Models\Review;
use App\Models\User;

class MovieAndReviewSeeder extends Seeder
{
    public function run(): void
    {
        // 1. เพิ่มหมวดหมู่ที่ยังขาดอยู่
        $genres = [
            'Adventure',
            'Thriller',
            'Documentary',
            'Music',
            'Western',
        ];

        foreach ($genres as $genre) {
            Type::firstOrCreate(['name' => $genre]);
        }

        // ดึง ID หมวดหมู่ไว้ใช้งาน
        $typeSciFi = Type::where('name', 'Sci-Fi')->first()?->id ?? 1;
        $typeAction = Type::where('name', 'Action')->first()?->id ?? 1;
        $typeDrama = Type::where('name', 'Drama')->first()?->id ?? 1;
        $typeAnimation = Type::where('name', 'Animation')->first()?->id ?? 1;
        $typeRomance = Type::where('name', 'Romance')->first()?->id ?? 1;
        $typeMusic = Type::where('name', 'Music')->first()?->id ?? 1;
        $typeThriller = Type::where('name', 'Thriller')->first()?->id ?? 1;

        $userIds = User::pluck('id')->toArray();
        if (empty($userIds)) {
            $userIds = [1];
        }

        // 2. ข้อมูลภาพยนตร์ 10 เรื่องใหม่
        $moviesData = [
            [
                'name' => 'ทะยานดาวกู้โลก',
                'original_title' => 'Interstellar',
                'year' => 2014,
                'review' => 'เมื่อโลกกำลังเผชิญกับวิกฤตความอดอยากและพายุฝุ่น ทีมนักสำรวจจึงต้องเดินทางผ่านรูหนอนในอวกาศ เพื่อหาดาวเคราะห์ดวงใหม่สำหรับมนุษยชาติ',
                'image' => 'https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg',
                'hours' => 2.8,
                'type_id' => $typeSciFi,
                'tmdb_id' => 157336,
            ],
            [
                'name' => 'ชนชั้นปรสิต',
                'original_title' => 'Parasite',
                'year' => 2019,
                'review' => 'เรื่องราวของครอบครัวยากจนในห้องใต้ดินที่ค่อยๆ แทรกซึมเข้าไปทำงานในคฤหาสน์ของครอบครัวเศรษฐี จนนำไปสู่เหตุการณ์ไม่คาดฝัน',
                'image' => 'https://image.tmdb.org/t/p/w500/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg',
                'hours' => 2.2,
                'type_id' => $typeThriller,
                'tmdb_id' => 496243,
            ],
            [
                'name' => 'อัศวินรัตติกาล',
                'original_title' => 'The Dark Knight',
                'year' => 2008,
                'review' => 'แบทแมนร่วมมือกับสารวัตรกอร์ดอนและฮาร์วีย์ เดนต์ ต่อสู้กับอาชญากรวายร้ายจอมป่วนเมืองอย่าง โจ๊กเกอร์ ที่สร้างความโกลาหลทั่วก็อตแธม',
                'image' => 'https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911r6m7haRef0WH.jpg',
                'hours' => 2.5,
                'type_id' => $typeAction,
                'tmdb_id' => 155,
            ],
            [
                'name' => 'มิติวิญญาณมหัศจรรย์',
                'original_title' => 'Spirited Away',
                'year' => 2001,
                'review' => 'จิฮิโระ เด็กหญิงวัย 10 ขวบ พลัดหลงเข้าไปในโลกแห่งวิญญาณ และต้องทำงานในโรงอาบน้ำของแม่มดยูบาบาเพื่อหาทางช่วยพ่อแม่ของเธอ',
                'image' => 'https://image.tmdb.org/t/p/w500/39wmItIWsg5sZMyRUHLkWBcuVCM.jpg',
                'hours' => 2.1,
                'type_id' => $typeAnimation,
                'tmdb_id' => 129,
            ],
            [
                'name' => 'อเวนเจอร์ส: เผด็จศึก',
                'original_title' => 'Avengers: Endgame',
                'year' => 2019,
                'review' => 'หลังจากธานอสดีดนิ้วล้างบางครึ่งจักรวาล สมาชิกอเวนเจอร์สที่รอดชีวิตต้องรวมพลังกันอีกครั้งเพื่อย้อนเวลากลับไปกอบกู้ทุกชีวิต',
                'image' => 'https://image.tmdb.org/t/p/w500/or06FN3Dka5tukK1e9sl16pB3iy.jpg',
                'hours' => 3.0,
                'type_id' => $typeAction,
                'tmdb_id' => 299534,
            ],
            [
                'name' => 'ไททานิค',
                'original_title' => 'Titanic',
                'year' => 1997,
                'review' => 'โศกนาฏกรรมความรักต่างชนชั้นระหว่างแจ็คกับโรส บนเรือสำราญไททานิคที่ถูกขนานนามว่าไม่มีวันจม ก่อนจะพุ่งชนภูเขาน้ำแข็งกลางมหาสมุทร',
                'image' => 'https://image.tmdb.org/t/p/w500/9xjZS2rlVxm8SFx8kPC3aIGCOYQ.jpg',
                'hours' => 3.2,
                'type_id' => $typeRomance,
                'tmdb_id' => 597,
            ],
            [
                'name' => 'ชอว์แชงค์ มิตรภาพ ความหวัง ความรอด',
                'original_title' => 'The Shawshank Redemption',
                'year' => 1994,
                'review' => 'แอนดี้ ดูเฟรนส์ นายธนาคารหนุ่มถูกตัดสินจำคุกตลอดชีวิตในเรือนจำชอว์แชงค์ในข้อหาที่เขาไม่ได้ก่อ และได้เรียนรู้ถึงความหวังและมิตรภาพที่แท้จริง',
                'image' => 'https://image.tmdb.org/t/p/w500/9cqNxx0GxF0bflZmeSMuL5tnGzr.jpg',
                'hours' => 2.4,
                'type_id' => $typeDrama,
                'tmdb_id' => 278,
            ],
            [
                'name' => 'หลับตาฝัน ถึงชื่อเธอ',
                'original_title' => 'Your Name.',
                'year' => 2016,
                'review' => 'เด็กหนุ่มในโตเกียวและเด็กสาวในชนบทพบว่าจิตใจของพวกเขาสลับร่างกันอย่างลึกลับในยามหลับ ก่อนจะค้นพบความจริงที่เชื่อมโยงถึงดาวหาง',
                'image' => 'https://image.tmdb.org/t/p/w500/q719jXXEzOoYaps6qFsRWa0Rbuq.jpg',
                'hours' => 1.8,
                'type_id' => $typeAnimation,
                'tmdb_id' => 372058,
            ],
            [
                'name' => 'ดูน ภาคสอง',
                'original_title' => 'Dune: Part Two',
                'year' => 2024,
                'review' => 'พอล อะทรีดีส ร่วมมือกับชานีและชาวเฟรเมนเพื่อล้างแค้นตระกูลฮาร์คอนเนนที่ทำลายครอบครัวของเขา พร้อมเผชิญหน้ากับชะตากรรมที่ไม่อาจเลี่ยง',
                'image' => 'https://image.tmdb.org/t/p/w500/czembW0RLEvuGIiqqY5Rbg1F3SX.jpg',
                'hours' => 2.7,
                'type_id' => $typeSciFi,
                'tmdb_id' => 693134,
            ],
            [
                'name' => 'นครดารา',
                'original_title' => 'La La Land',
                'year' => 2016,
                'review' => 'เรื่องราวความรักและความฝันของนักเปียโนแจ๊สหนุ่มและนักแสดงสาวไฟแรงในนครลอสแอนเจลิส ท่ามกลางบทเพลงอันไพเราะและทางแยกของชีวิต',
                'image' => 'https://image.tmdb.org/t/p/w500/uDO8zWDhfWwoFdKS4fzkVJb0Rf0.jpg',
                'hours' => 2.1,
                'type_id' => $typeMusic,
                'tmdb_id' => 313369,
            ],
        ];

        $createdMovies = [];
        foreach ($moviesData as $idx => $m) {
            $movie = Activity::firstOrCreate(
                ['tmdb_id' => $m['tmdb_id']],
                array_merge($m, [
                    'user_id' => $userIds[$idx % count($userIds)],
                    'is_approved' => 1,
                    'status' => 'approved',
                ])
            );
            $createdMovies[] = $movie;
        }

        // 3. เพิ่มรีวิว 10 รีวิว
        $reviewsData = [
            [
                'movie_index' => 0, // Interstellar
                'rating' => 10,
                'comment' => 'เป็นผลงานระดับมาสเตอร์พีซอย่างแท้จริง งานภาพอวกาศและดนตรีประกอบของ Hans Zimmer ทรงพลังมาก ดึงอารมณ์ร่วมได้ตั้งแต่ต้นจนจบ เป็นหนังที่ดูจบแล้วตราตรึงใจไปอีกนาน',
                'is_spoiler' => 0,
            ],
            [
                'movie_index' => 1, // Parasite
                'rating' => 10,
                'comment' => 'บทภาพยนตร์ยอดเยี่ยมไร้ที่ติ ตีแผ่ความเหลื่อมล้ำทางสังคมได้คมกริบ การเปลี่ยนโทนเรื่องจากคอมเมดี้กลายเป็นทริลเลอร์ทำได้แนบเนียนจนขนลุก คุ้มค่ารางวัลออสการ์ทุกประการ',
                'is_spoiler' => 0,
            ],
            [
                'movie_index' => 2, // The Dark Knight
                'rating' => 10,
                'comment' => 'ฮีธ เลดเจอร์ ถ่ายทอดบทโจ๊กเกอร์ออกมาได้บ้าคลั่งและมีเสน่ห์จนกลายเป็นตำนาน ยกระดับหนังซูเปอร์ฮีโร่ให้กลายเป็นภาพยนตร์อาชญากรรมแนวจิตวิทยาระดับขึ้นหิ้ง',
                'is_spoiler' => 0,
            ],
            [
                'movie_index' => 3, // Spirited Away
                'rating' => 9,
                'comment' => 'อนิเมชั่นจิบลิที่งดงามเหนือกาลเวลา โลกแห่งจินตนาการที่เต็มไปด้วยความหมายแฝงเกี่ยวกับการเติบโตของเด็กสาว ดูซ้ำกี่ครั้งก็ยังประทับใจ',
                'is_spoiler' => 0,
            ],
            [
                'movie_index' => 4, // Avengers: Endgame
                'rating' => 9,
                'comment' => 'ฉากรวมพลฮีโร่ช่วงท้ายเรื่องคือที่สุดของความไฮป์ เป็นบทสรุปมหากาพย์ 11 ปีของ MCU ที่สมเกียรติและน่าประทับใจมาก มีทั้งฉากซึ้ง ฉากมันส์ และความทรงจำมากมาย',
                'is_spoiler' => 1,
            ],
            [
                'movie_index' => 5, // Titanic
                'rating' => 9,
                'comment' => 'ความรักสุดคลาสสิกที่ดูกี่รอบก็น้ำตาไหล งานสร้างเรือไททานิคสมจริงจนน่าทึ่ง เพลง My Heart Will Go On ขึ้นมาทีไรก็ขนลุกทุกที',
                'is_spoiler' => 0,
            ],
            [
                'movie_index' => 6, // The Shawshank Redemption
                'rating' => 10,
                'comment' => 'หนังที่สอนความหมายของคำว่า "ความหวัง" ได้ดีที่สุดในประวัติศาสตร์ภาพยนตร์ มิตรภาพระหว่างแอนดี้กับเรดลึกซึ้งกินใจ ฉากจบคือที่สุดของความโล่งใจและอิ่มเอม',
                'is_spoiler' => 1,
            ],
            [
                'movie_index' => 7, // Your Name.
                'rating' => 9,
                'comment' => 'งานภาพละเอียดแสงสีสวยงามตระการตา เพลงประกอบเพราะทุกเพลง การเล่าเรื่องสลับร่างช่วงแรกน่ารัก พอเข้าครึ่งหลังดราม่าเข้มข้นจนลุ้นตามทุกวินาที',
                'is_spoiler' => 0,
            ],
            [
                'movie_index' => 8, // Dune: Part Two
                'rating' => 9,
                'comment' => 'ประสบการณ์การชมภาพยนตร์ไซไฟระดับมหากาพย์ งานภาพ งานเสียง และสเกลความยิ่งใหญ่ระดับปรากฏการณ์ ทิโมธีและเซนดายาแสดงได้ยอดเยี่ยมมาก',
                'is_spoiler' => 0,
            ],
            [
                'movie_index' => 9, // La La Land
                'rating' => 8,
                'comment' => 'เพลงเพราะ เต้นสวย แสงสีวิเศษมาก ฉากจบทำร้ายจิตใจแต่สมจริงตามความเป็นจริงของชีวิต ความฝันกับความรักบางครั้งก็ไม่ได้เดินไปด้วยกันเสมอไป',
                'is_spoiler' => 1,
            ],
        ];

        foreach ($reviewsData as $i => $r) {
            $movie = $createdMovies[$r['movie_index']] ?? null;
            if ($movie) {
                Review::create([
                    'user_id' => $userIds[$i % count($userIds)],
                    'activity_id' => $movie->id,
                    'rating' => $r['rating'],
                    'comment' => $r['comment'],
                    'is_spoiler' => $r['is_spoiler'],
                ]);
            }
        }
    }
}
