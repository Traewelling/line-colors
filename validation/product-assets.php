<?php

include_once "./common.php";

echo "Building CSV database by Wikidata QIDs" . PHP_EOL;
if(!is_dir("../website/assets/products")){
mkdir("../website/assets/products");}
array_map('unlink', glob("../website/assets/products/*.svg"));

$fp = fopen("../website/assets/products/product-categories.csv", 'w');
fputcsv($fp, $productKeys, ',', '"', '');

foreach ($productSources as $brand) {
    if($brand["iconName"] == "" || $brand["shortName"] == "") {


        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "https://www.wikidata.org/w/rest.php/wikibase/v1/entities/items/" . $brand["wikidataQid"]);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, "traewelling-product-categories");

        $json = json_decode(curl_exec($curl), true);

        curl_close($curl);

        if($brand["iconName"] == "") {
            $iconName = $json["statements"]["P154"][0]["value"]["content"];
            $iconName = str_replace(" ", "_", $iconName);

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "https://commons.wikimedia.org/w/api.php?action=query&format=json&prop=imageinfo&iiprop=url&titles=File:" . $iconName);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_USERAGENT, "traewelling-product-categories");

            $imageInfo = json_decode(curl_exec($curl), true);

            curl_close($curl);

            $key = array_keys($imageInfo["query"]["pages"])[0];
            $url = $imageInfo["query"]["pages"][$key]["imageinfo"][0]["url"];

            $commonName = strtolower($iconName);
            $commonName = str_replace("_", "-", $commonName);
            $commonName = str_replace(".svg", "", $commonName);

            $svg = "../website/assets/products/" . $commonName . ".svg";

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_USERAGENT, "traewelling-product-categories");

            file_put_contents($svg, curl_exec($curl));

            $output = null;
            $retval = null;

            exec("rsvg-convert -f svg -a -h 24 " . $svg . " -o " . $svg . " 2>&1", $output, $retval);
            exec("svgcleaner " . $svg . " " . $svg . " 2>&1", $output, $retval);
            $brand["iconName"] = $commonName;
        }
        if($brand["shortName"] == "") {
            $brand["shortName"] = $json["statements"]["P1813"][0]["value"]["content"]["text"];
        }
    } else if($brand["iconName"] != "") {
        $link = "../website/assets/products/" . $brand["iconName"] . ".svg";
        if(!is_file($link)){
        link("../products/" . $brand["iconName"] . ".svg", $link);}
    }
    fputcsv($fp, array_values($brand), ',', '"', '');
}

fclose($fp);
