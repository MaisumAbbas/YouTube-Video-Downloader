<?php
require_once "class.youtube.php";
$yt  = new YouTubeDownloader();
$downloadLinks ='';
$error='';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $videoLink = $_POST['video_link'];
    $vid = $yt->getYouTubeCode($videoLink);
    if($vid) {
        $result = $yt->processVideo($vid);

        if($result) {
            //print_r($result);
            $info = $result['info'];
            $downloadLinks = $result['videos'];

            $videoInfo = json_decode($info['player_response']);

            $title = $videoInfo->videoDetails->title;
            $thumbnail = $videoInfo->videoDetails->thumbnail->thumbnails{0}->url;
        }
        else {
            $error = "The following video is licensed. Therefore, it can't be downlaoded.";
        }

    }
}
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>YouTube Video Downloader</title>
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .formSmall {
            width: 700px;
            margin: 20px auto 20px auto;
        }
    </style>

</head>
<body>
    <div class="container">
        <form class="form-style-9" method="POST" action = "">
            <ul>
                <li><h5>YouTube Video Downlaoder</h5></li>
                <li>
                    <input type="text" name="video_link" class="field-style field-split align-left" placeholder="Paste YouTube Video Url Here" required/>
                </li>
                <br>
                <li>
                    <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Submit"/>
                </li>
            </ul>
        </form>
        
        <?php if($error) :?>
            <div style="color:red;font-weight: bold;text-align: center"><?php print $error?></div>
        <?php endif;?>

        <?php if($downloadLinks):?>
        <div class="row formSmall">
            <div class="col-lg-3">
                <img src="<?php print $thumbnail?>">
            </div>
            <div class="col-lg-9">
                <?php print $title?>
            </div>
        </div>

        <table class="table formSmall">
            <tr>
                <th>Type</th>
                <th>Quality</th>
                <th>Download</th>
            </tr>
            <?php foreach ($downloadLinks as $video) :?>
                <tr>
                    <td><?php print $video['type']?></td>
                    <td><?php print $video['quality']?></td>
                    <td><a href="downloader.php?link=<?php print urlencode($video['link'])?>&title=<?php print urlencode($title)?>&type=<?php print urlencode($video['type'])?>">Download</a> </td>
                </tr>
            <?php endforeach;?>
        </table>
        <?php endif;?>
    </div>
</body>
</html>