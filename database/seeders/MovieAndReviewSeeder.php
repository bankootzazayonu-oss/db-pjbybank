<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Type;
use App\Models\Activity;
use App\Models\Review;
use App\Models\User;
use App\Models\Admin;
use App\Models\Platform;
use App\Models\Collection;
use App\Models\CollectionItem;

class MovieAndReviewSeeder extends Seeder
{
    public function run(): void
    {
        $adminEntity = Admin::firstOrCreate(
            ['Admin_ID' => 'A0001'],
            [
                'Username' => 'admin',
                'Email' => 'admin@admin.com',
                'Password' => Hash::make('password'),
            ]
        );

        $admin = User::firstOrCreate(
            ['User_ID' => 'U0001'],
            [
                'Username' => 'admin',
                'Email' => 'admin@admin.com',
                'Password' => Hash::make('password'),
                'Role' => 'admin',
            ]
        );

        $regularUser = User::firstOrCreate(
            ['User_ID' => 'U0002'],
            [
                'Username' => 'user',
                'Email' => 'user@user.com',
                'Password' => Hash::make('password'),
                'Role' => 'user',
            ]
        );

        $genres = [
            ['Genre_ID' => 'G01', 'Genre_Name' => 'Action'],
            ['Genre_ID' => 'G02', 'Genre_Name' => 'Drama'],
            ['Genre_ID' => 'G03', 'Genre_Name' => 'Comedy'],
            ['Genre_ID' => 'G04', 'Genre_Name' => 'Sci-Fi'],
            ['Genre_ID' => 'G05', 'Genre_Name' => 'Animation'],
            ['Genre_ID' => 'G06', 'Genre_Name' => 'Romance'],
            ['Genre_ID' => 'G07', 'Genre_Name' => 'Horror'],
            ['Genre_ID' => 'G08', 'Genre_Name' => 'Adventure'],
            ['Genre_ID' => 'G09', 'Genre_Name' => 'Thriller'],
            ['Genre_ID' => 'G10', 'Genre_Name' => 'Documentary'],
            ['Genre_ID' => 'G11', 'Genre_Name' => 'Music'],
            ['Genre_ID' => 'G12', 'Genre_Name' => 'Western'],
        ];

        foreach ($genres as $g) {
            Type::firstOrCreate(
                ['Genre_ID' => $g['Genre_ID']],
                ['Genre_Name' => $g['Genre_Name']]
            );
        }

        $moviesData = [
            [
                'Movie_ID' => 'M00001',
                'Title' => 'ทะยานดาวกู้โลก',
                'Original_Title' => 'Interstellar',
                'Release_Year' => 2014,
                'Description' => 'เมื่อโลกกำลังเผชิญกับวิกฤตความอดอยากและพายุฝุ่น ทีมนักสำรวจจึงต้องเดินทางผ่านรูหนอนในอวกาศ เพื่อหาดาวเคราะห์ดวงใหม่สำหรับมนุษยชาติ',
                'Poster' => 'https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg',
                'Duration_Hours' => 2.8,
                'Genre_ID' => 'G04',
                'TMDB_ID' => 157336,
                'Submitted_By' => 'U0001',
                'Approval_Status' => 'approved',
                'platforms' => ['P001', 'P004'],
            ],
            [
                'Movie_ID' => 'M00002',
                'Title' => 'ชนชั้นปรสิต',
                'Original_Title' => 'Parasite',
                'Release_Year' => 2019,
                'Description' => 'ครอบครัวตระกูลคิมที่ยากจน วางแผนแทรกซึมเข้าไปทำงานในบ้านเศรษฐีตระกูลพัคทีละคน จนนำไปสู่เหตุการณ์ไม่คาดคิดที่เปลี่ยนชีวิตของทุกคน',
                'Poster' => 'https://image.tmdb.org/t/p/w500/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg',
                'Duration_Hours' => 2.2,
                'Genre_ID' => 'G02',
                'TMDB_ID' => 496243,
                'Submitted_By' => 'U0001',
                'Approval_Status' => 'approved',
                'platforms' => ['P001', 'P006'],
            ],
            [
                'Movie_ID' => 'M00003',
                'Title' => 'อัศวินรัตติกาล',
                'Original_Title' => 'The Dark Knight',
                'Release_Year' => 2008,
                'Description' => 'แบทแมนต้องเผชิญหน้ากับวายร้ายโรคจิต "โจ๊กเกอร์" ที่สร้างความโกลาหลไปทั่วก็อตแธม และบีบให้แบทแมนต้องเลือกระหว่างความยุติธรรมกับการเป็นฮีโร่',
                'Poster' => 'https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911r6m7haRef0WH.jpg',
                'Duration_Hours' => 2.5,
                'Genre_ID' => 'G01',
                'TMDB_ID' => 155,
                'Submitted_By' => 'U0001',
                'Approval_Status' => 'approved',
                'platforms' => ['P004', 'P003'],
            ],
            [
                'Movie_ID' => 'M00004',
                'Title' => 'มิติวิญญาณมหัศจรรย์',
                'Original_Title' => 'Spirited Away',
                'Release_Year' => 2001,
                'Description' => 'จิฮิโระ เด็กหญิงวัย 10 ขวบ พลัดหลงเข้าไปในโลกแห่งวิญญาณ และต้องทำงานในโรงอาบน้ำของแม่มดยูบาบาเพื่อช่วยพ่อแม่ที่ถูกสาปให้กลายเป็นหมู',
                'Poster' => 'https://image.tmdb.org/t/p/w500/39wmItIWsg5sZMyRUHLkWBcuVCM.jpg',
                'Duration_Hours' => 2.1,
                'Genre_ID' => 'G05',
                'TMDB_ID' => 129,
                'Submitted_By' => 'U0002',
                'Approval_Status' => 'approved',
                'platforms' => ['P001'],
            ],
            [
                'Movie_ID' => 'M00005',
                'Title' => 'อเวนเจอร์ส: เผด็จศึก',
                'Original_Title' => 'Avengers: Endgame',
                'Release_Year' => 2019,
                'Description' => 'หลังธานอสดีดนิ้วล้างบางครึ่งจักรวาล เหล่าอเวนเจอร์สที่เหลือรอดต้องรวบรวมกำลังใจและวางแผนย้อนเวลาเพื่อกอบกู้สิ่งที่สูญเสียไปกลับคืนมา',
                'Poster' => 'https://image.tmdb.org/t/p/w500/or06FN3Dka5tukK1e9sl16pB3iy.jpg',
                'Duration_Hours' => 3.0,
                'Genre_ID' => 'G01',
                'TMDB_ID' => 299534,
                'Submitted_By' => 'U0001',
                'Approval_Status' => 'approved',
                'platforms' => ['P002'],
            ],
            [
                'Movie_ID' => 'M00006',
                'Title' => 'ไททานิค',
                'Original_Title' => 'Titanic',
                'Release_Year' => 1997,
                'Description' => 'เรื่องราวความรักต่างชนชั้นระหว่างแจ็คกับโรส บนเรือสำราญไททานิคที่ยิ่งใหญ่ที่สุด ก่อนจะประสบภัยพิบัติชนภูเขาน้ำแข็งกลางมหาสมุทรแอตแลนติก',
                'Poster' => 'https://image.tmdb.org/t/p/w500/9xjZS2rlVxm8SFx8kPC3aIGCOYQ.jpg',
                'Duration_Hours' => 3.2,
                'Genre_ID' => 'G06',
                'TMDB_ID' => 597,
                'Submitted_By' => 'U0002',
                'Approval_Status' => 'approved',
                'platforms' => ['P002'],
            ],
            [
                'Movie_ID' => 'M00007',
                'Title' => 'มิตรภาพ ความหวัง ความสุข',
                'Original_Title' => 'The Shawshank Redemption',
                'Release_Year' => 1994,
                'Description' => 'แอนดี้ ดูเฟรนส์ นายธนาคารหนุ่มถูกตัดสินจำคุกตลอดชีวิตในคดีที่เขาไม่ได้ก่อ ณ เรือนจำชอว์แชงค์ เขาใช้สติปัญญาและความหวังเปลี่ยนชีวิตของตัวเองและเพื่อนนักโทษ',
                'Poster' => 'https://image.tmdb.org/t/p/w500/9cqNxx0GxF0bflZmeSMuL5tnGzr.jpg',
                'Duration_Hours' => 2.4,
                'Genre_ID' => 'G02',
                'TMDB_ID' => 278,
                'Submitted_By' => 'U0001',
                'Approval_Status' => 'approved',
                'platforms' => ['P001'],
            ],
            [
                'Movie_ID' => 'M00008',
                'Title' => 'หลับตาฝัน ถึงชื่อเธอ',
                'Original_Title' => 'Your Name.',
                'Release_Year' => 2016,
                'Description' => 'ทาคิ เด็กหนุ่มมัธยมในโตเกียว และมิตสึฮะ เด็กสาวในชนบท สลับร่างกันอย่างลึกลับในความฝัน จนนำไปสู่ความผูกพันและภารกิจกอบกู้เมืองจากภัยพิบัติ',
                'Poster' => 'https://image.tmdb.org/t/p/w500/q719jXXEzOoYaps6qFsRWa0RbuW.jpg',
                'Duration_Hours' => 1.8,
                'Genre_ID' => 'G05',
                'TMDB_ID' => 372058,
                'Submitted_By' => 'U0002',
                'Approval_Status' => 'approved',
                'platforms' => ['P001', 'P007'],
            ],
            [
                'Movie_ID' => 'M00009',
                'Title' => 'ดูน ภาคสอง',
                'Original_Title' => 'Dune: Part Two',
                'Release_Year' => 2024,
                'Description' => 'พอล อะทรีเดส ร่วมมือกับชานีและชาวเฟรเมนเพื่อล้างแค้นตระกูลฮาร์คอนเนนที่ทำลายครอบครัวของเขา พร้อมเผชิญหน้ากับชะตากรรมที่ไม่อาจหลีกเลี่ยง',
                'Poster' => 'https://image.tmdb.org/t/p/w500/1pdfLvkbY9ohJlCjQH2CZjjYVvJ.jpg',
                'Duration_Hours' => 2.8,
                'Genre_ID' => 'G04',
                'TMDB_ID' => 693134,
                'Submitted_By' => 'U0001',
                'Approval_Status' => 'approved',
                'platforms' => ['P004'],
            ],
            [
                'Movie_ID' => 'M00010',
                'Title' => 'นครดารา',
                'Original_Title' => 'La La Land',
                'Release_Year' => 2016,
                'Description' => 'เซบาสเตียน นักเปียโนแจ๊สหนุ่ม และมีอา นักแสดงสาวผู้มีความฝัน พบรักกันในลอสแองเจลิส แต่เมื่อความสำเร็จเริ่มเข้ามา ความสัมพันธ์ของทั้งคู่ก็ต้องเผชิญกับบททดสอบ',
                'Poster' => 'https://image.tmdb.org/t/p/w500/uDO8zWDhfWwoFdKS4fzkVJt0Rf0.jpg',
                'Duration_Hours' => 2.1,
                'Genre_ID' => 'G06',
                'TMDB_ID' => 313369,
                'Submitted_By' => 'U0002',
                'Approval_Status' => 'approved',
                'platforms' => ['P001', 'P003'],
            ],
        ];

        foreach ($moviesData as $data) {
            $platforms = $data['platforms'] ?? [];
            unset($data['platforms']);

            $movie = Activity::firstOrCreate(['Movie_ID' => $data['Movie_ID']], $data);
            if (!empty($platforms)) {
                $movie->platforms()->syncWithoutDetaching($platforms);
            }
        }

        $reviewsData = [
            [
                'Review_ID' => 'REV0000001',
                'Movie_ID' => 'M00001',
                'User_ID' => 'U0001',
                'Score' => 10.0,
                'Review_Text' => 'เป็นผลงานระดับมาสเตอร์พีซอย่างแท้จริง งานภาพอวกาศและดนตรีประกอบของ Hans Zimmer ทรงพลังมาก ดึงอารมณ์ร่วมได้ตั้งแต่ต้นจนจบ เป็นหนังที่ดูจบแล้วตราตรึงใจไปอีกนาน',
                'Spoiler' => 0,
            ],
            [
                'Review_ID' => 'REV0000002',
                'Movie_ID' => 'M00002',
                'User_ID' => 'U0002',
                'Score' => 10.0,
                'Review_Text' => 'บทภาพยนตร์ยอดเยี่ยมไร้ที่ติ ตีแผ่ความเหลื่อมล้ำทางสังคมได้คมกริบ การเปลี่ยนโทนเรื่องจากคอมเมดี้กลายเป็นทริลเลอร์ทำได้แนบเนียนจนขนลุก คุ้มค่ารางวัลออสการ์ทุกประการ',
                'Spoiler' => 0,
            ],
            [
                'Review_ID' => 'REV0000003',
                'Movie_ID' => 'M00003',
                'User_ID' => 'U0001',
                'Score' => 10.0,
                'Review_Text' => 'ฮีธ เลดเจอร์ ถ่ายทอดบทโจ๊กเกอร์ออกมาได้บ้าคลั่งและมีเสน่ห์จนกลายเป็นตำนาน ยกระดับหนังซูเปอร์ฮีโร่ให้กลายเป็นภาพยนตร์อาชญากรรมแนวจิตวิทยาระดับขึ้นหิ้ง',
                'Spoiler' => 0,
            ],
            [
                'Review_ID' => 'REV0000004',
                'Movie_ID' => 'M00004',
                'User_ID' => 'U0002',
                'Score' => 9.0,
                'Review_Text' => 'อนิเมชั่นจิบลิที่งดงามเหนือกาลเวลา โลกแห่งจินตนาการที่เต็มไปด้วยความหมายแฝงเกี่ยวกับการเติบโตของเด็กสาว ดูซ้ำกี่ครั้งก็ยังประทับใจ',
                'Spoiler' => 0,
            ],
            [
                'Review_ID' => 'REV0000005',
                'Movie_ID' => 'M00005',
                'User_ID' => 'U0001',
                'Score' => 9.0,
                'Review_Text' => 'ฉากรวมพลฮีโร่ช่วงท้ายเรื่องคือที่สุดของความไฮป์ เป็นบทสรุปมหากาพย์ 11 ปีของ MCU ที่สมเกียรติและน่าประทับใจมาก มีทั้งฉากซึ้ง ฉากมันส์ และความทรงจำมากมาย',
                'Spoiler' => 1,
            ],
            [
                'Review_ID' => 'REV0000006',
                'Movie_ID' => 'M00006',
                'User_ID' => 'U0002',
                'Score' => 9.0,
                'Review_Text' => 'ความรักสุดคลาสสิกที่ดูกี่รอบก็น้ำตาไหล งานสร้างเรือไททานิคสมจริงจนน่าทึ่ง เพลง My Heart Will Go On ขึ้นมาทีไรก็ขนลุกทุกที',
                'Spoiler' => 0,
            ],
            [
                'Review_ID' => 'REV0000007',
                'Movie_ID' => 'M00007',
                'User_ID' => 'U0001',
                'Score' => 10.0,
                'Review_Text' => 'หนังที่สอนความหมายของคำว่า "ความหวัง" ได้ดีที่สุดในประวัติศาสตร์ภาพยนตร์ มิตรภาพระหว่างแอนดี้กับเรดลึกซึ้งกินใจ ฉากจบคือที่สุดของความโล่งใจและอิ่มเอม',
                'Spoiler' => 1,
            ],
            [
                'Review_ID' => 'REV0000008',
                'Movie_ID' => 'M00008',
                'User_ID' => 'U0002',
                'Score' => 9.0,
                'Review_Text' => 'งานภาพละเอียดแสงสีสวยงามตระการตา เพลงประกอบเพราะทุกเพลง การเล่าเรื่องสลับร่างช่วงแรกน่ารัก พอเข้าครึ่งหลังดราม่าเข้มข้นจนลุ้นตามทุกวินาที',
                'Spoiler' => 0,
            ],
            [
                'Review_ID' => 'REV0000009',
                'Movie_ID' => 'M00009',
                'User_ID' => 'U0001',
                'Score' => 9.0,
                'Review_Text' => 'ประสบการณ์การชมภาพยนตร์ไซไฟระดับมหากาพย์ งานภาพ งานเสียง และสเกลความยิ่งใหญ่ระดับปรากฏการณ์ ทิโมธีและเซนดายาแสดงได้ยอดเยี่ยมมาก',
                'Spoiler' => 0,
            ],
            [
                'Review_ID' => 'REV0000010',
                'Movie_ID' => 'M00010',
                'User_ID' => 'U0002',
                'Score' => 8.0,
                'Review_Text' => 'เพลงเพราะ เต้นสวย แสงสีวิเศษมาก ฉากจบทำร้ายจิตใจแต่สมจริงตามความเป็นจริงของชีวิต ความฝันกับความรักบางครั้งก็ไม่ได้เดินไปด้วยกันเสมอไป',
                'Spoiler' => 1,
            ],
        ];

        foreach ($reviewsData as $r) {
            Review::firstOrCreate(['Review_ID' => $r['Review_ID']], $r);
        }

        $tierList = Collection::firstOrCreate(
            ['Tier_ID' => 'T000000001'],
            [
                'User_ID' => 'U0001',
                'Tier_Title' => 'ภาพยนตร์ขึ้นหิ้งระดับตำนาน',
                'Tier_Labels' => [
                    'S' => 'Masterpiece',
                    'A' => 'Must Watch',
                    'B' => 'Good',
                    'C' => 'Decent',
                    'D' => 'Skip',
                ],
            ]
        );

        $items = [
            ['Tier_ID' => 'T000000001', 'Movie_ID' => 'M00001', 'Tier_Level' => 'S'],
            ['Tier_ID' => 'T000000001', 'Movie_ID' => 'M00002', 'Tier_Level' => 'S'],
            ['Tier_ID' => 'T000000001', 'Movie_ID' => 'M00003', 'Tier_Level' => 'S'],
            ['Tier_ID' => 'T000000001', 'Movie_ID' => 'M00004', 'Tier_Level' => 'A'],
            ['Tier_ID' => 'T000000001', 'Movie_ID' => 'M00005', 'Tier_Level' => 'A'],
        ];

        foreach ($items as $item) {
            CollectionItem::firstOrCreate(
                ['Tier_ID' => $item['Tier_ID'], 'Movie_ID' => $item['Movie_ID']],
                ['Tier_Level' => $item['Tier_Level']]
            );
        }
    }
}
