<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Player::class, 500)->create();
        factory(App\School::class, 9)->create();
        factory(App\Tournament::class, 50)->create();
        factory(App\User::class, 9)->create();
        factory(App\SchoolAttendant::class, 50)->create();

        $highSchools = $this->getArrayOfHighSchools();
        $conferenceOptions = ['3A','4A','5A','6A'];
        foreach($highSchools as $highschool) {
            $randomConferenceOption = $conferenceOptions[array_rand($conferenceOptions)];
            DB::table('schools')->insert([
                'name' => $highschool,
                'address' => 'Oklahoma City',
                'conference' => $randomConferenceOption,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }
    }

    public function getArrayOfHighSchools()
    {
        $highSchoolsAsArray = explode(PHP_EOL, $this->listOfHighSchools);
        $tidyHighSchools = [];
        foreach($highSchoolsAsArray as $highSchool) {
            //to strip away useless info and comma
            $highSchoolNameOnly = explode(",", $highSchool);
            array_push($tidyHighSchools, $highSchoolNameOnly[0]);
        }
        return $tidyHighSchools;
    }

    protected string $listOfHighSchools = "Cave Springs High School, Bunch
Stilwell High School, Stilwell
Watts High School, Watts
Westville High School, Westville
Aline-Cleo High School, Aline
Burlington High School, Burlington
Cherokee High School, Cherokee
Timberlake High School, Helena
Atoka High School, Atoka
Caney High School, Caney
Stringtown High School, Stringtown
Tushka High School, Atoka
Balko High School, Balko
Beaver High School, Beaver
Forgan High School, Forgan
Turpin High School, Turpin
Elk City High School, Elk City
Erick High School, Erick
Merritt High School, Elk City
Sayre High School, Sayre
Canton High School, Canton
Geary High School, Geary
Okeene Junior-Senior High School, Okeene
Watonga High School, Watonga
Achille High School, Achille
Bennington High School, Bennington
Caddo High School, Caddo
Calera High School, Calera
Colbert High School, Colbert
Durant High School, Durant
Rock Creek High School, Bokchito
Silo High School, Durant
Anadarko High School, Anadarko
Apache High School, Apache
Binger-Oney High School, Binger
Carnegie High School, Carnegie
Cement High School, Cement
Cyril High School, Cyril
Fort Cobb-Broxton High School, Fort Cobb
Gracemont High School, Gracemont
Hinton High School, Hinton
Hydro-Eakly High School, Hydro
Lookeba-Sickles High School, Lookeba
Calumet High School, Calumet
El Reno High School, El Reno
Mustang High School, Mustang
Piedmont High School, Piedmont
Southwest Covenant School, Yukon
Union City High School, Union City
Yukon High School, Yukon
Ardmore High School, Ardmore
Dickson High School, Ardmore
Fox Junior-Senior High School, Fox
Healdton High School, Healdton
Lone Grove High School, Lone Grove
Plainview High School, Ardmore
Springer High School, Springer
Wilson High School, Wilson
Hulbert Junior-Senior High School, Hulbert
Markoma Christian School, Tahlequah
Sequoyah High School, Tahlequah
Tahlequah High School, Tahlequah
Keys High School, Park Hill
Valleyville high school, Fortsmith
Boswell High School, Boswell
Fort Towson High School, Fort Towson
Hugo High School, Hugo
Soper High School, Soper
Boise City High School, Boise City
Felt High School, Felt
Moore High School, Oklahoma City
Westmoore High School, Oklahoma City
Southmoore High School, Oklahoma City
Norman High School, Norman
Norman North High School, Norman
Lexington High School, Lexington
Little Axe High School, Norman
Noble High School, Noble
Coalgate High School, Coalgate
Olney High School, Clarita
Tupelo High School, Tupelo
Cache High School, Cache
Chattanooga High School, Chattanooga
Eisenhower High School, Lawton
Elgin High School, Elgin
Fletcher High School, Fletcher
Geronimo High School, Geronimo
Indiahoma High School, Indiahoma
Lawton Christian School, Lawton
Lawton High School, Lawton
MacArthur High School, Lawton
Sterling High School, Sterling
Big Pasture High School, Randlett
Temple High School, Temple
Walters High School, Walters
Bluejacket High School, Bluejacket
Ketchum High School, Ketchum
Vinita High School, Vinita
Welch Junior-Senior High School, Welch
White Oak High School, Vinita
Bristow High School, Bristow
Depew High School, Depew
Drumright High School, Drumright
Kellyville High School, Kellyville
Kiefer High School, Kiefer
Mannford High School, Mannford
Mounds High School, Mounds
Oilton High School, Oilton
Olive High School, Drumright
Sapulpa High School, Sapulpa
Arapaho High School, Arapaho
Butler High School, Butler
Clinton High School, Clinton
Thomas-Fay-Custer Unified High School, Thomas
Weatherford High School, Weatherford
Colcord High School, Colcord
Grove High School, Grove
Jay High School, Jay
Kansas High School, Kansas
Oaks-Mission High School, Oaks
Seiling Junior-Senior High School, Seiling
Taloga High School, Taloga
Vici High School, Vici
Leedey High School, Leedey
Arnett High School, Arnett
Fargo High School, Fargo
Gage High School, Gage
Shattuck Senior High School, Shattuck
Chisholm High School, Enid
Cimarron High School, Lahoma
Covington-Douglas High School, Covington
Drummond High School, Drummond
Enid High School, Enid
Garber High School, Garber
Kremlin-Hillsdale High School, Kremlin
Oklahoma Bible Academy, Enid
Pioneer-Pleasant Vale High School, Waukomis
Waukomis High School, Waukomis
Elmore City-Pernell High School, Elmore City
Lindsay High School, Lindsay
Maysville High School, Maysville
Paoli High School, Paoli
Pauls Valley High School, Pauls Valley
Stratford Junior-Senior High School, Stratford
Wynnewood High School, Wynnewood
Alex Junior-Senior High School, Alex
Amber-Pocasset High School, Amber
Bridge Creek High School, Blanchard
Chickasha High School, Chickasha
Minco High School, Minco
Ninnekah Senior High School, Ninnekah
Rush Springs High School, Rush Springs
Tuttle High School, Tuttle
Verden High School, Verden
Deer Creek-Lamont High School, Lamont
Medford High School, Medford
Pond Creek-Hunter Junior-Senior High School, Pond Creek
Granite High School, Granite
Lakeside School, Granite
Mangum High School, Magnum
Hollis High School, Hollis
Buffalo High School, Buffalo
Laverne High School, Laverne
Keota High School, Keota
Kinta High School, Kinta
McCurtain High School, McCurtain
Stigler High School, Stigler
Wetumka High School, Wetumka
Calvin High School, Calvin
Dustin High School, Dustin
Holdenville High School, Holdenville
Moss High School, Holdenville
Stuart High School, Stuart
Altus High School, Altus
Blair High School, Blair
Duke High School, Duke
Eldorado High School, Eldorado
Navajo High School, Altus
Olustee High School, Olustee
Ringling High School, Ringling
Ryan High School, Ryan
Waurika High School, Waurika
Coleman High School, Coleman
Milburn High School, Milburn
Mill Creek High School, Mill Creek
Tishomingo High School, Tishomingo
Wapanucka High School, Wapanucka
Blackwell High School, Blackwell
Braman High School, Braman
Newkirk High School, Newkirk
Ponca City High School, Ponca City
Tonkawa High School, Tonkawa
Cashion High School, Cashion
Dover High School, Dover
Hennessey High School, Hennessey
Kingfisher High School, Kingfisher
Lomega High School, Omega
Okarche High School, Okarche
Hobart High School, Hobart
Lone Wolf Junior-Senior High School, Lone Wolf
Mountain View-Gotebo High School, Mountain View
Snyder High School, Snyder
Panola High School, Panola
Red Oak High School, Red Oak
Wilburton High School, Wilburton
Arkoma High School, Arkoma
Bokoshe High School, Bokoshe
Buffalo Valley High School, Talihina
Cameron High School, Cameron
Heavener High School, Heavener
Howe High School, Howe
Le Flore High School, Le Flore
Panama High School, Panama
Pocola High School, Pocola
Poteau High School, Poteau
Spiro High School, Spiro
Talihina High School, Talihina
Whitesboro High School, Whitesboro
Wister High School, Wister
Agra High School, Agra
Carney High School, Carney
Chandler High School, Chandler
Davenport High School, Davenport
Meeker High School, Meeker
Prague High School, Prague
Stroud High School, Stroud
Wellston High School, Wellston
Coyle High School, Coyle
Crescent High School, Crescent
Guthrie High School, Guthrie
Mulhall-Orlando High School, Orlando
Marietta High School, Marietta
Thackerville High School, Thackerville
Turner High School, Burneyville
Fairview High School, Fairview
Ringwood High School, Ringwood
Kingston High School, Kingston
Madill High School, Madill
Adair High School, Adair
Chouteau-Mazie High School, Chouteau
Locust Grove High School, Locust Grove
Pryor High School, Pryor
Salina High School, Salina
Blanchard High School, Blanchard
Dibble High School, Dibble
Newcastle High School, Newcastle
Purcell High School, Purcell
Washington High School, Washington
Wayne High School, Wayne
Battiest High School, Battiest
Broken Bow High School, Broken Bow
Eagletown High School, Eagletown
Haworth High School, Haworth
Idabel High School, Idabel
Smithville High School, Smithville
Valliant High School, Valliant
Wright City High School, Wright City
Checotah High School, Checotah
Eufaula High School, Eufaula
Hanna High School, Hanna
Midway High School, Council Hill
Davis High School, Davis
Oklahoma School for the Deaf, Sulphur
Sulphur High School, Sulphur
Boynton High School, Boynton
Braggs High School, Braggs
Fort Gibson High School, Fort Gibson
Haskell High School, Haskell
Hilldale High School, Muskogee
Muskogee High School, Muskogee
Oktaha High School, Oktaha
Oklahoma School for the Blind, Muskogee
Porum High School, Porum
Warner High School, Warner
Webbers Falls High School, Webbers Falls
Billings High School, Billings
Frontier High School, Red Rock
Morrison High School, Morrison
Perry High School, Perry
Nowata High School, Nowata
Oklahoma Union High School, South Coffeyville
South Coffeyville High School, South Coffeyville
Boley High School, Boley
Graham High School, Weleetka
Mason High School, Mason
Okemah High School, Okemah
Paden High School, Paden
Weleetka High School, Weleetka
Choctaw High School, Choctaw
Deer Creek High School, Edmond
Capitol Hill High School, Oklahoma City
Classen School of Advanced Studies, Oklahoma City
Douglass High School, Oklahoma City
Dove Science Academy, Oklahoma City
Emerson High School, Oklahoma City
U. S. Grant High School, Oklahoma City
Harding Charter Preparatory High School, Oklahoma City
John Marshall High School, Oklahoma City
New John Marshall High School, Oklahoma City
Northeast Academy for Health Sciences and Engineering, Oklahoma City
Northwest Classen High School, Oklahoma City
Oklahoma Centennial High School, Oklahoma City
Southeast High School, Oklahoma City
Star Spencer High School, Spencer
Dungee High School, Spencer
John Wesley Charter School, Oklahoma City
Carl Albert High School, Midwest City
Del City High School, Del City
Midwest City High School, Midwest City
Bethany High School, Bethany
Bishop McGuinness High School, Oklahoma City
Casady School, Oklahoma City
Christian Heritage Academy, Del City
Crooked Oak High School, Oklahoma City
Crossings Christian School, Oklahoma City
Destiny Christian School, Del City
Edmond Memorial High School, Edmond
Edmond North High School, Edmond
Edmond Santa Fe High School, Edmond
Harrah High School, Harrah
Heritage Hall School, Oklahoma City
Jones High School, Jones
Luther High School, Luther
Millwood High School, Oklahoma City
Mount Saint Mary High School, Oklahoma City
Oklahoma Academy, Harrah
Oklahoma Christian School, Edmond
Oklahoma School of Science and Mathematics, Oklahoma City
Providence Hall Classical Christian School, Edmond
Putnam City High School, Oklahoma City
Putnam City North High School, Oklahoma City
Putnam City West High School, Oklahoma City
Western Heights High School, Oklahoma City
Beggs High School, Beggs
Dewar High School, Dewar
Henryetta High School, Henryetta
Morris High School, Morris
Okmulgee High School, Okmulgee
Preston High School, Preston
Schulter High School, Schulter
Wilson High School, Henryetta
Barnsdall High School, Barnsdall
Hominy High School, Hominy
Pawhuska High School, Pawhuska
Prue High School, Prue
Shidler High School, Shidler
Skiatook High School, Skiatook
Woodland High School, Fairfax
Wynona High School, Wynona
Afton High School, Afton
Commerce High School, Commerce
Fairland High School, Fairland
Miami High School, Miami
Picher-Cardin High School, Picher
Quapaw High School, Quapaw
Wyandotte High School, Wyandotte
Cleveland High School, Cleveland
Pawnee High School, Pawnee
Cushing High School, Cushing
Glencoe High School, Glencoe
Perkins-Tryon High School, Perkins
Ripley High School, Ripley
Stillwater High School, Stillwater
Yale High School, Yale
Canadian High School, Canadian
Crowder High School, Crowder
Haileyville High School, Haileyville
Hartshorne High School, Hartshorne
Indianola High School, Indianola
Kiowa High School, Kiowa
McAlester High School, McAlester
Pittsburg Public School, Pittsburg
Quinton High School, Quinton
Savanna High School, Savanna
McAlester Christian Academy, McAlester
Lakewood Christian High School, McAlester
Ada Senior High School, Ada
Allen High School, Allen
Byng High School, Ada
Latta High School, Ada
McLish High School, Fittstown
Roff High School, Roff
Stonewall High School, Stonewall
Vanoss High School, Ada
Liberty Academy, Shawnee
Asher High School, Asher
Bethel High School, Shawnee
Dale High School, Dale
Earlsboro High School, Earlsboro
Macomb High School, Macomb
Maud High School, Maud
McLoud High School, McLoud
Shawnee High School, Shawnee
Tecumseh High School, Tecumseh
Wanette High School, Wanette
Antlers High School, Antlers
Clayton High School, Clayton
Moyers High School, Moyers
Rattan High School, Rattan
Cheyenne High School, Cheyenne
Hammon High School, Hammon
Leedey High School, Leedey
Reydon High School, Reydon
Sweetwater High School, Sweetwater
Catoosa High School, Catoosa
Chelsea High School, Chelsea
Claremore High School, Claremore
Foyil High School, Foyil
Inola High School, Inola
Oologah-Talala High School, Oologah
Sequoyah High School, Claremore
Verdigris High School, Claremore
Bowlegs High School, Bowlegs
Butner High School, Cromwell
Konawa High School, Konawa
New Lima High School, Wewoka
Sasakwa High School, Sasakwa
Seminole High School, Seminole
Strother High School, Seminole
Varnum High School, Seminole
Wewoka High School, Wewoka
Central High School, Sallisaw
Gans High School, Gans
Gore High School, Gore
Muldrow High School, Muldrow
Roland High School, Roland
Sallisaw High School, Sallisaw
Vian High School, Vian
Bray-Doyle High School, Marlow
Central High High School, Marlow
Comanche High School, Comanche
Duncan High School, Duncan
Empire High School, Duncan
Marlow High School, Marlow
Velma-Alma High School, Velma
Goodwell High School, Goodwell
Guymon High School, Guymon
Hardesty High School, Hardesty
Hooker High School, Hooker
Texhoma High School, Texhoma
Tyrone High School, Tyrone
Yarbrough High School, Goodwell
Davidson High School, Davidson
Frederick High School, Frederick
Grandfield High School, Grandfield
Tipton High School, Tipton
Jenks High School, Jenks
Owasso High School, Owasso
Owasso Alternative High School, Owasso
Owasso Mid-High School, Owasso
Booker T. Washington High School, Tulsa
Central High School, Tulsa
East Central High School, Tulsa
Edison Preparatory School, Tulsa
McLain High School, Tulsa
Memorial High School, Tulsa
Nathan Hale High School, Tulsa
Will Rogers High School, Tulsa
Daniel Webster High School, Tulsa
Union High School, Tulsa
Berryhill High School, Tulsa
Bishop Kelley High School, Tulsa
Bixby High School, Bixby
Broken Arrow Senior High, Broken Arrow
Broken Arrow North Intermediate High School, Broken Arrow
Broken Arrow South Intermediate High School, Broken Arrow
Cascia Hall Preparatory School, Tulsa
Collinsville High School, Collinsville
Dove Science Academy, Tulsa
Glenpool High School, Glenpool
Liberty High School, Mounds
Holland Hall, Tulsa
Metro Christian Academy, Tulsa
Mingo Valley Christian School, Tulsa
Moriah Christian Academy, Sand Springs
Charles Page High School, Sand Springs
Saint Augustine Acamady, Tulsa
Sperry High School, Sperry
Tulsa School of Arts and Sciences, Tulsa
Wright Christian Academy, Tulsa
Coweta High School, Coweta
Destiny Christian Academy, Wagoner
Okay High School, Okay
Porter Consolidated High School, Porter
Wagoner High School, Wagoner
Bartlesville High School, Bartlesville
Caney Valley High School, Ramona
Copan High School, Copan
Dewey High School, Dewey
Wesleyan Christian High School, Bartlesville
Burns Flat-Dill City High School, Burns Fla
Canute High School, Canute
Cordell High School, Cordell
Corn Bible Academy, Corn
Blanche Thomas High School, Sentinel
Washita Heights High School, Corn
Alva High School, Alva
Freedom High School, Freedom
Waynoka High School, Waynoka
Fort Supply High School, Fort Supply
Mooreland High School, Mooreland
Sharon-Mutual High School, Mutual
Woodward High School, Woodward";
}
