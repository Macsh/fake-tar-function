<?php
$elements = $argv;
array_shift($elements);
$paths = []; 
$archive = "output.mytar";
$a = 0;
$b = 0;


function add_to_paths($elements){
    global $paths;

    foreach($elements as $key => $element){
        if(is_file($element)) {
            array_push($paths, $element);
        }
        elseif(is_dir($element)) {
            $foldername = basename($element);
            $scanned_directory = array_diff(scandir($element), array('..', '.'));
            $paths["./".$foldername] = $scanned_directory;
        }
    }
    return $paths;
}

function add_to_file($paths){
    global $paths;
    global $archive;
    $myfile = fopen($archive, "a");

    foreach($paths as $key => $file){
        if(is_array($file)){
            foreach($file as $subfolders => $files){
                if(is_array($files)){
                    //add_to_file($files);
                }
                else{
                    $filetype = pathinfo($files, PATHINFO_EXTENSION);
                    if($filetype !== "text" && !empty($filetype)){
                        $dir = $key. "/" .$files;
                        $filecontent = file_get_contents($dir);
                        $data = base64_encode($filecontent);
                        $filewrite = "|File Name => [" .$files. "]//Path => [" .$key. "/]//Type => [" .$filetype. "]//Content => [" .$data."]\n";
                        echo "\nAjout à l'archive du fichier : ".$files. " se trouvant dans le dossier : " .$key. "\n";
                        fwrite($myfile, $filewrite);
                    } 
                    else{
                        $dir = $key. "/" .$files;
                        $filecontent = file_get_contents($dir);
                        $filewrite = "|File Name => [" .$files. "]//Path => [" .$key. "/]//Type => [" .$filetype. "]//Content => [" .$filecontent."]\n";
                        echo "\nAjout à l'archive du fichier : ".$files. " se trouvant dans le dossier : " .$key. "\n";
                        fwrite($myfile, $filewrite);
                    }
                }  
            } 
        }
        else{
            $filepath = "./";
            $filetype = pathinfo($file, PATHINFO_EXTENSION);
            $filecontent = file_get_contents($file);
            if($filetype !== "text" && !empty($filetype)){
                $data = base64_encode($filecontent);
                $filewrite = "|File Name => [" .$file. "]//Path => [" .$filepath. "]//Type => [" .$filetype. "]//Content => [" .$data."]\n" ;
                echo "\nAjout à l'archive du fichier : ".$file. "\n";
                fwrite($myfile, $filewrite);
            }
            else{
                $filewrite = "|File Name => [" .$file. "]//Path => [" .$filepath. "]//Type => [" .$filetype. "]//Content => [" .$filecontent."]\n" ;
                echo "\nAjout à l'archive du fichier : ".$file. "\n";
                fwrite($myfile, $filewrite);
            }  
        } 
    }
    echo "\n\nArchive créé avec succès !\n\n\n";
    fclose($myfile);
    user_input();
}

function user_input(){
    global $b;
    global $a;
    global $paths;
    if($b == 3){
        delete_files($paths);
    } 
    elseif($b == 4){
        return false;
    }
    else {
        echo "Voulez-vous supprimer les fichiers originaux ?\n";
        echo "
        1. Supprimer\n
        2. Ne pas supprimer\n
        3. Supprimer (ne plus redemander)\n
        4. Ne pas supprimer (ne plus redemander)\n
        5. Arrêter et quitter\n\n";
        $a = readline("\nFaites votre choix : ");
        return $a;
    }
    
}

function delete_files($paths){
    global $a;
    global $b;
    global $paths;

    foreach($paths as $key => $file){
        if(is_array($file)){
            foreach($file as $subfolders => $files){
                $dirfiles = $key. "/" .$files;
                $dir = $key. "/";
                    unlink($dirfiles);
                    rmdir($dir);
                } 
        }
        else{
            
            unlink($file);
        } 
    }
    echo "\nFichiers originaux supprimés avec succès !\n";
}

add_to_paths($elements);
add_to_file($paths);

if($a == 5){
    $a = 0;
} 
elseif($a == 3){
    $a = 0;
    $b = 3;
    delete_files($paths);
} 
elseif($a == 4){
    $a = 0;
    $b = 4;
    return false;
} 
elseif($a == 2){
    $a = 0;
    $b = 2;
    return false;
} 
elseif($a == 1){
    $a = 0;
    $b = 1;
    delete_files($paths);
} 
?>