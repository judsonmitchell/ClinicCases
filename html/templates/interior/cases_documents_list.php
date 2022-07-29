<?php

if (isset($search)) {
}

if (!isset($update)) {
    $radio_name = rand();
    $grid_id = rand();
    $list_id = rand();
?>

    <div class="user_display ui-widget ui-corner-bottom user_widget" tabindex="1">

    </div>

    <div class="case_documents_toolbar">

        <div class="form__control">
            <input id="caseDocumentsSearch-<?php echo $case_id ?>" data-caseid="<?php echo $case_id ?>" type="text" class="documents_search" placeholder=" " value="<?php if (isset($search)) {
                                                                                                                                                                        echo $search;
                                                                                                                                                                    } ?>"> <label for="caseDocumentsSearch-<?php echo $case_id ?>">Search Titles</label>
        </div>
        <div class="case_documents_toolbar--right">
            <button class="button--secondary">
                <img src="html/ico/new-folder.png" alt="New Folder Icon" /> <span>&nbsp;New Folder</span>
            </button>
            <button class="button--secondary">
                <img src="html/ico/new-document.png" alt="New Document Icon" /> <span>&nbsp;New Document</span>
            </button>
            <button class="button--secondary">
                <img src="html/ico/upload.png" alt="Upload Icon" /> <span>&nbsp;Upload</span>
            </button>
            <div class="documents_view_chooser list">
                <div class="documents_view_chooser--grid" data-caseid="<?php echo $case_id ?>">
                    <img src="html/ico/grid-unselected.png" alt="">
                    <p>Grid</p>
                </div>
                <div class="documents_view_chooser--list" data-caseid="<?php echo $case_id ?>">
                    <img src="html/ico/list-selected.png" alt="">
                    <p>List</p>
                </div>
            </div>

        </div>

    </div>
    <div class="case_documents_submenu">
        <img src="html/ico/house.png"> <a href="#" class="doc_trail_home">Home</a>/
        <span class="path_display" path=""><a href="#" class="doc_trail_item active" path=""></a></span>
    </div>
    <div class="case_detail_panel_casenotes">
    <?php
}
if (empty($folders) and empty($documents)) {

    echo "<br /><span class='docs_empty'>No documents found.</a>";
    die;
}
    ?>

    <table id="doc_list">
        <thead>
            <tr>
                <td></td>
                <td>Name</td>
                <?php if (isset($search)) { ?> <td>Folder</td><?php } ?><td>Date</td>
                <td>By</td>
            </tr>
        </thead>
        <tbody>
            <?php


            foreach ($folders as $folder) {
                $user = username_to_fullname($dbh, $folder['username']);
                $date = extract_date_time($folder['date_modified']);
                if (strrchr($folder['folder'], '/')) {
                    $folder_name = substr(strrchr($folder['folder'], '/'), 1);
                } else {
                    $folder_name = $folder['folder'];
                }
                $folder_path = $folder['folder'];
            ?>

                <tr class="doc_item folder" path="<?php echo $folder_path; ?>" data-id="<?php echo $folder['id']; ?>">
                    <td width="10%"><img src="html/ico/folder.png"></td>
                    <td><?php echo htmlspecialchars(rawurldecode($folder_name), ENT_QUOTES, 'UTF-8') ?> </td>
                    <?php if (isset($search)) { ?>
                        <td><?php echo htmlspecialchars(rawurldecode($folder['containing_folder']), ENT_QUOTES, 'UTF-8'); ?></td>
                    <?php } ?>
                    <td><?php echo $date; ?></td>
                    <td><?php echo $user; ?></td>
                </tr>

            <?php
            }
            foreach ($documents as $document) {
                $icon = get_icon($document['type']);
                $user = username_to_fullname($dbh, $document['username']);
                $date = extract_date_time($document['date_modified']);
            ?>
                <tr id="doc_<?php echo $document['id']; ?>" class="doc_item item <?php echo $document['type']; ?>" data-id="<?php echo $document['id']; ?>">
                    <td><img src="<?php echo $icon; ?>"></td>
                    <td><?php echo htmlspecialchars(rawurldecode($document['name']), ENT_QUOTES, 'UTF-8'); ?> </td>
                    <?php if (isset($search)) { ?>
                        <td><?php echo htmlspecialchars(rawurldecode($document['folder']), ENT_QUOTES, 'UTF-8'); ?></td>
                    <?php } ?>
                    <td><?php echo $date; ?></td>
                    <td><?php echo $user; ?></td>
                </tr>

            <?php

            }
            ?>

        </tbody>
    </table>
    </div>