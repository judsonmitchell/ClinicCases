<?php

if (isset($search)){
    echo "Search Results for $search";
}
?>
<table id="doc_list">
    <thead>
    <tr><td></td><td>Name</td><td>Folder</td><td>Date</td><td>By</td></tr>
    </thead>
    <tbody>
<?php


    foreach ($folders as $folder){
        $user = username_to_fullname($dbh,$folder['username']);
        $date = extract_date_time($folder['date_modified']);
        if (strrchr($folder['folder'],'/')) {
            $folder_name = substr(strrchr($folder['folder'],'/'),1);
        } else {
            $folder_name = $folder['folder'];
        }
        $folder_path = $folder['folder'];
?>

        <tr class="doc_item folder" path="<?php echo $folder_path; ?>" data-id="<?php echo $folder['id']; ?>">
        <td width="10%"><img src="html/ico/folder.png"></td>
        <td><?php echo htmlspecialchars(rawurldecode($folder_name), ENT_QUOTES,'UTF-8') ?> </td>
        <td><?php echo $folder['folder']; ?></td>
        <td><?php echo $date; ?></td>
        <td><?php echo $user; ?></td></tr>

<?php
    }
    foreach ($documents as $document){
        $icon = get_icon($document['type']);
        $user = username_to_fullname($dbh,$document['username']);
        $date = extract_date_time($document['date_modified']);
?>
        <tr id="doc_<?php echo $document['id']; ?>" class="doc_item item <?php echo $document['type']; ?>" data-id="<?php echo $document['id']; ?>">
        <td><img src="<?php echo $icon; ?>"></td>
        <td><?php echo htmlspecialchars(rawurldecode($document[name]), ENT_QUOTES,'UTF-8') ?> </td>
        <td><?php echo $document['folder']; ?></td>
        <td><?php echo $date; ?></td>
        <td><?php echo $user; ?></td></tr>

<?php

    }
?>

    </tbody>
</table>
</div>
