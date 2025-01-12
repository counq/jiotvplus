<?php

// Determine HTTP or HTTPS
if (array_key_exists('HTTPS', $_SERVER) && $_SERVER["HTTPS"] == "on") {
    $HTTP = "https";
} else {
    $HTTP = "http";
}

$Host_Url = $HTTP . "://" . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);

// Check if Channel.json file exists
if (file_exists('Channel.json')) {
    $channel_Json = json_decode(@file_get_contents('Channel.json', true));
    
    // Define category order
    $category_order = [
        'Entertainment', 'Movies', 'Kids', 'Sports', 'Infotainment', 'Lifestyle',
        'News', 'Business News', 'Devotional', 'Educational', 'Music'
    ];

    // Check for category order issues
    foreach ($channel_Json as $channel) {
        if (!in_array($channel->genre, $category_order)) {
            echo 'Warning: Category "' . $channel->genre . '" not in predefined order.' . PHP_EOL;
        }
    }

    // Sort channels by category and then by view count (assuming the view count is available)
    usort($channel_Json, function ($a, $b) use ($category_order) {
        $category_compare = array_search($a->genre, $category_order) - array_search($b->genre, $category_order);
        if ($category_compare === 0) {
            // Assuming view count is available in the objects for sorting
            return $b->view_count - $a->view_count; 
        }
        return $category_compare;
    });

    // Generate M3U playlist
    $print_data = '#EXTM3U' . PHP_EOL;
    
    // Define SonyLiv channels
    $sonyliv_channels = [
        '#EXTINF:-1 tvg-logo="https://c.evidon.com/pub_logos/2796-2021122219404475.png" group-title="SonyLiv", Sony Kal',
        'https://spt-sonykal-1-us.lg.wurl.tv/playlist.m3u8',
        '#EXTINF:-1 tvg-id="1000009246" tvg-logo="https://sonypicturesnetworks.com/images/logos/SET-LOGO-HD.png" group-title="SonyLiv", SET HD',
        'https://dai.google.com/ssai/event/HgaB-u6rSpGx3mo4Xu3sLw/master.m3u8',
        '#EXTINF:-1 tvg-id="1000009248" tvg-logo="https://sonypicturesnetworks.com/images/logos/SONY%20SAB%20HD_WHITE.png" group-title="SonyLiv", Sony SAB HD',
        'https://dai.google.com/ssai/event/UI4QFJ_uRk6aLxIcADqa_A/master.m3u8',
        '#EXTINF:-1 tvg-id="1000009273" tvg-logo="https://sonypicturesnetworks.com/images/logos/SONY%20PAL.png" group-title="SonyLiv", Sony PAL',
        'https://dai.google.com/ssai/event/rPzF28qORbKZkhci_04fdQ/master.m3u8',
        '#EXTINF:-1 tvg-id="1000001971" tvg-logo="https://sonypicturesnetworks.com/images/logos/SONY%20YAY.png" group-title="SonyLiv", Sony YAY',
        'https://dai.google.com/ssai/event/40H5HfwWTZadFGYkBTqagg/master.m3u8',
        '#EXTINF:-1 tvg-id="1000009255" tvg-logo="https://sonypicturesnetworks.com/images/logos/SONY%20AATH.png" group-title="SonyLiv", Sony AATH',
        'https://dai.google.com/ssai/event/pSVzGmMpQR6jdmwwJg87OQ/master.m3u8',
        '#EXTINF:-1 tvg-id="1000009259" tvg-logo="https://sonypicturesnetworks.com/images/logos/Sony_MARATHI.png" group-title="SonyLiv", Sony Marathi',
        'https://dai.google.com/ssai/event/-_w3Jbq3QoW-mFCM2YIzxA/master.m3u8',
        '#EXTINF:-1 tvg-id="1000009252" tvg-logo="https://sonypicturesnetworks.com/images/logos/SBBCE_LOGO_NEW_PNG.png" group-title="SonyLiv", Sony BBC Earth HD',
        'https://dai.google.com/ssai/event/V73ovbgASP-xGvQQOukwTQ/master.m3u8',
        '#EXTINF:-1 tvg-id="1000009258" tvg-logo="https://sonypicturesnetworks.com/images/logos/PIX%20HD_WHITE.png" group-title="SonyLiv", Sony PIX HD',
        'https://dai.google.com/ssai/event/8FR5Q-WfRWCkbMq_GxZ77w/master.m3u8',
        '#EXTINF:-1 tvg-id="1000009253" tvg-logo="https://sonypicturesnetworks.com/images/logos/Sony_WAH.png" group-title="SonyLiv", Sony WAH',
        'https://dai.google.com/ssai/event/H_ZvXWqHRGKpHcdDE5RcDA/master.m3u8',
        '#EXTINF:-1 tvg-id="1000009249" tvg-logo="https://sonypicturesnetworks.com/images/logos/Sony_MAX.png" group-title="SonyLiv", Sony MAX',
        'https://dai.google.com/ssai/event/oJ-TGgVFSgSMBUoTkauvFQ/master.m3u8',
        '#EXTINF:-1 tvg-id="1000009247" tvg-logo="https://sonypicturesnetworks.com/images/logos/Sony_MAX-HD_WHITE.png" group-title="SonyLiv", Sony MAX HD',
        'https://dai.google.com/ssai/event/Qyqz40bSQriqSuAC7R8_Fw/master.m3u8',
        '#EXTINF:-1 tvg-id="1000044878" tvg-logo="https://sonypicturesnetworks.com/images/logos/Sony_MAX2.png" group-title="SonyLiv", Sony MAX2',
        'https://dai.google.com/ssai/event/4Jcu195QTpCNBXGnpw2I6g/master.m3u8',
        '#EXTINF:-1 tvg-id="1000009280" tvg-logo="https://sonypicturesnetworks.com/images/logos/SONY_SportsTen1_SD_Logo_CLR.png" group-title="SonyLiv", Sony TEN 1',
        'https://dai.google.com/ssai/event/4_pnLi2QTe6bRGvvahRbfg/master.m3u8',
        '#EXTINF:-1 tvg-id="1000009279" tvg-logo="https://sonypicturesnetworks.com/images/logos/SONY_SportsTen2_SD_Logo_CLR.png" group-title="SonyLiv", Sony TEN 2',
        'https://dai.google.com/ssai/event/nspQRqO5RmC06VmlPrTwkQ/master.m3u8',
        '#EXTINF:-1 tvg-id="1000009283" tvg-logo="https://sonypicturesnetworks.com/images/logos/SONY_SportsTen3_SD_Logo_CLR.png" group-title="SonyLiv", Sony TEN 3',
        'https://dai.google.com/ssai/event/9kocjiLUSf-erlSrv3d4Mw/master.m3u8',
        '#EXTINF:-1 tvg-id="1000119187" tvg-logo="https://sonypicturesnetworks.com/images/logos/SONY_SportsTen4_SD_Logo_CLR.png" group-title="SonyLiv", Sony TEN 4',
        'https://dai.google.com/ssai/event/hInaEKUJSziZAGv9boOdjg/master.m3u8',
        '#EXTINF:-1 tvg-id="1000009281" tvg-logo="https://sonypicturesnetworks.com/images/logos/SONY_SportsTen5_SD_Logo_CLR.png" group-title="SonyLiv", Sony TEN 5',
        'https://dai.google.com/ssai/event/S-q8I27RRzmkb-OIdoaiAw/master.m3u8',
        '#EXTINF:-1 tvg-id="1000009276" tvg-logo="https://sonypicturesnetworks.com/images/logos/SONY_SportsTen1_HD_Logo_CLR.png" group-title="SonyLiv", Sony TEN 1 HD',
        'https://dai.google.com/ssai/event/yeYP86THQ4yl7US8Zx5eug/master.m3u8',
        '#EXTINF:-1 tvg-id="1000009277" tvg-logo="https://sonypicturesnetworks.com/images/logos/SONY_SportsTen2_HD_Logo_CLR.png" group-title="SonyLiv", Sony TEN 2 HD',
        'https://dai.google.com/ssai/event/Syu8F41-R1y_JmQ7x0oNxQ/master.m3u8',
        '#EXTINF:-1 tvg-id="1000009278" tvg-logo="https://sonypicturesnetworks.com/images/logos/SONY_SportsTen3_HD_Logo_CLR.png" group-title="SonyLiv", Sony TEN 3 HD',
        'https://dai.google.com/ssai/event/nmQFuHURTYGQBNdUG-2Qdw/master.m3u8',
        '#EXTINF:-1 tvg-id="1000119186" tvg-logo="https://sonypicturesnetworks.com/images/logos/SONY_SportsTen4_HD_Logo_CLR.png" group-title="SonyLiv", Sony TEN 4 HD',
        'https://dai.google.com/ssai/event/x4LxWUcVSIiDaq1VCM7DSA/master.m3u8',
        '#EXTINF:-1 tvg-id="1000009275" tvg-logo="https://sonypicturesnetworks.com/images/logos/SONY_SportsTen5_HD_Logo_CLR.png" group-title="SonyLiv", Sony TEN 5 HD',
        'https://dai.google.com/ssai/event/DD7fA-HgSUaLyZp9AjRYxQ/master.m3u8'
    ];

    // Add SonyLiv channels at the top below the EPG guide
    foreach ($sonyliv_channels as $channel) {
        $print_data .= $channel . PHP_EOL;
    }

    // Add all other channels
    foreach ($channel_Json as $for_js) {
        $print_data .= '#KODIPROP:inputstream.adaptive.license_type=com.widevine.alpha' . PHP_EOL;
        $print_data .= '#KODIPROP:inputstream.adaptive.license_key=' . $Host_Url . 'host.php?id=' . $for_js->id . PHP_EOL;
        $print_data .= '#EXTVLCOPT:http-user-agent=plaYtv/7.1.5 (Linux;Android 13) ExoPlayerLib/2.11.7' . PHP_EOL;
        $print_data .= '#EXTINF:-1 tvg-id="' . $for_js->id . '" tvg-logo="' . $for_js->logo . '" group-title="' . $for_js->genre . '",' . $for_js->title . PHP_EOL;
        $print_data .= $Host_Url . 'url.php?id=' . $for_js->id . PHP_EOL;
    }

    print($print_data);
} else {
    exit('Channel Missing');
}
?>
