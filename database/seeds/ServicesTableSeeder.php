<?php

use App\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Storage::disk('public')->deleteDirectory('services');
        Storage::disk('public')->makeDirectory('services');
        $services = [
            [
                'Regular Boosting',
                '12',
                'Regular Boosting allows our customers to buy the desired number of ranked wins. All you have to do is choose how many ranked wins you would like to have on.',
                'Popular',
                'Regular Boosting makes it possible to buy a guaranteed number of wins of your choice. We created regular boosting to be fast and easy, providing an efficient but high-quality service.',
            ],
            [
                'League Boosting',
                '5',
                'At Boostroyal.com, we sell guaranteed tier and division boosts of your choice. You just have to pick a tier and a division that you desire and our task is to do the rest.',
                null,
                'League boosting allow the customer to purchase guaranteed tier and division boosts. We designed league boosting to be the fastest option but at the same time providing high-quality service.'
            ],
            [
                'LOR Boosting',
                '10',
                'Reach the highest ranks in the game before anybody else! Hire Master professionals to boost your tier and division in Runeterra.',
                'New',
                null,
            ],
            [
                'Duo Queue Boosting',
                '6',
                'With Duo Queue, you can stay interactive while you are being elo boosted. By purchasing, you will get boosted while playing duo with a high lol ELO booster.',
                null,
                'This type of ELO boost makes it possible for the client to stay active playing LoL.By purchasing it, we provide a professional ELO booster who will play duo queue ranked games with the customer.'
            ],
            [
                'Buy lol smurf account',
                '11',
                'You are able to buy from a high variety of unranked lol smurf accounts. All of them are level 30 and loaded with either blue essence or champions.',
                null,
                'Below, you can browse from different types of unranked smurf accounts. Select the one that meets your needs, and by completing your payment, you receive the account\'s log in details.'
            ],
            [
                'Account market',
                '1',
                'You can browse through different LoL accounts. You can see the elo, number of champions and skins on each account.',
                null,
                'LoL accounts on sale. In this menu you can choose to buy different types of League of Legends accounts.'
            ],
            [
                'TFT Boosting',
                '4',
                'TFT Boosting allows the client to get any ranked rewards in Teamfight Tactics, with the help of professional boosters in the form of a high-quality service.',
                null,
                null,
            ],
            [
                'Champion Mastery',
                '3',
                'Want to look good with all of your champions independently? Champion mastery boost is here to get any of your champions to tier 7 from 0 at no time.',
                null,
                'Champion mastery boost makes it possible to reach tier 7 with each champion you desire. All you have to do is to choose the champion and we will do the rest.',
            ],
            [
                'Placement matches',
                '9',
                'Placement matches are here to give you the perfect start. By boosting your 10 placement games, you will be able to start the season with the highest lol elo possible.',
                null,
                'Placement matches are here to give you the perfect start. By choosing the division where you finished last season and the number of wins you want us to complete, we can give you some serious results to start with.'
            ],
            [
                'Clash Boosting',
                '13',
                'Start winning your Clash games! Hire Challenger players to boost your team\'s performance in LoL Clashes.',
                'New',
                null,
            ],
            [
                'ELO boosting packs',
                '2',
                'ELO boosting packs make it possible to buy lol elo boost at a discounted price. There is a variety of lol elo boosting packs you can choose from, with different starting and goal tiers.',
                'Best Value',
                'Below, you can see the range of ELO boosting packs we offer. You just have to choose the one that suits you and the best and then proceed to checkout. After buying the ELO boosting pack, you just put in your log-in details and our ELO booster will do the rest.'
            ],
            [
                'Promotion boosting',
                '10',
                'One of the most valuable ELO boost service. Promotion boosting is the best option for League players, who are looking for a simple way to win their promotion series.',
                null,
                'If you are only one step away from your desired rank, Promotion boosting makes sure that your long-awaited promotion is in good hands.',
            ],
            [
                'Account leveling',
                '7',
                'Account leveling lol boosting service makes it possible to reach LoL level you desire. Want to start playing ranked quickly? Don\'t hesitate.',
                null,
                'Account leveling service makes it possible to reach the LoL level you desire.',
            ],
            [
                'Normal matches',
                '8',
                'Normal matches boost is a great way to reach level 30 super-fast. It also works great to erase the ranked restriction games while we are gathering blue essence.',
                null,
                'Normal matches boosts allow you to purchase progress in the normal game mode. You can choose from normal games or normal wins, both providing fast and significant results.'
            ],
            [
                'Coaching',
                '14',
                'Coaching is an efficient way to improve your skills and mindset in LoL. We provide experienced high elo players for your service.',
                null,
                null,
            ]
        ];
        foreach ($services as $service) {
            Service::create([
                'name' => $service[0],
                'image' => "img/circles/$service[1].png",
                'description' => $service[2],
                'status' => $service[3],
                'subtitle' => $service[4] ?? $service[2],
                'slug' => sluggify($service[0]),
            ]);
        }
    }
}
