<?php
class Video{
    public static function getVideoDuration($filePath) {
        return shell_exec("ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $filePath");
    }

    public static function updateDuration($duration) {
        
        $hours = floor($duration / 3600);
        $mins = floor(($duration - ($hours*3600)) / 60);
        $secs = floor($duration % 60);
        
        $hours = ($hours < 1 ? "" : $hours . ":");
        $mins .= ":";
        $secs = ($secs < 10 ? "0" . $secs : $secs);
        $duration = $hours.$mins.$secs;

        return $duration;
    }

    public static function generateThumbnails($filePath, $outputPath){
        $duration = (int)self::getVideoDuration($filePath);
        $outputPath = $_SERVER['DOCUMENT_ROOT'].'/'.$outputPath;
        if($duration > 5){
            $interval = $duration * 0.8;
        }else{
            $interval = 0;
        }
        $interval = round($interval);
        $cmd = "ffmpeg -i $filePath -ss $interval -vf scale=320:-1 -vframes 1 $outputPath 2>&1";
        $outputLog = array();
        exec($cmd, $outputLog, $returnCode);
        if($returnCode != 0) {  // command failed
            foreach($outputLog as $line) {
                return 'err';
            }
        }
    }

    public static function convertVideoToFormat($tempFilePath, $finalFilePath) {
        $cmd = "ffmpeg -i $tempFilePath $finalFilePath 2>&1";
        $outputLog = array();
        exec($cmd, $outputLog, $returnCode);
        
        if($returnCode != 0) {  // command failed
            foreach($outputLog as $line) {
                return 'err';
            }
        }
    }
}