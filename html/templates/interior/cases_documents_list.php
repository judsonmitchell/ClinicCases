<?php


if (!isset($update)) {
    $radio_name = rand();
    $grid_id = rand();
    $list_id = rand();
?>
    <div class="case_details_documents" data-caseid="<?php echo $case_id ?>" data-currentpath="Home" data-layout="List">

        <div class="user_display ui-widget ui-corner-bottom user_widget" tabindex="1">

        </div>

        <div class="case_documents_toolbar">

            <div class="form__control search">
                <input id="caseDocumentsSearch-<?php echo $case_id ?>" data-caseid="<?php echo $case_id ?>" type="text" class="documents_search" placeholder=" " value="<?php if (isset($search)) {
                                                                                                                                                                        } ?>"> <label for="caseDocumentsSearch-<?php echo $case_id ?>">Search Titles <span><img src="html/ico/search.png" /></span></label>
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
            <a href="#" class="doc_trail_home">Home</a>
            <span class="path_display" path=""><a href="#" class="doc_trail_item active" path=""></a></span>
        </div>
        <div class="case_detail_panel documents">
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

                    <tr class="doc_item folder doc_item_folder" data-filename='<?php echo $folder_name ?>' data-path="<?php echo $folder_path; ?>" data-id="<?php echo $folder['id']; ?>" data-caseid='<?php echo $case_id; ?>'>
                        <td><img src="html/ico/folder.png"></td>
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
                    <tr id="doc_<?php echo $document['id']; ?>" class="doc_item item <?php echo $document['type']; ?>" data-id="<?php echo $document['id']; ?> " data-filename='<?php echo $document['name'] ?>' data-caseid='<?php echo $document['case_id'] ?>' data-type='<?php echo $document['type'] ?>' data-path='<?php $document['folder'] ?>'>
                        <td>
                            <?php if ($document['type'] != 'ccd') {
                                if ($document['type'] == 'url') {
                                    echo '<a href="' . $document['local_file_name'] . '" target="_blank">';
                                } else {
                                    echo '<a href="' . CC_DOC_PATH . '/' . $document['local_file_name'] . '" download="' . $document['name'] . '">';
                                }
                            }
                            ?>
                            <img src="<?php echo $icon; ?>">
                            <?php if ($document['type'] != 'ccd') {
                                echo  '</a>';
                            } ?>
                        </td>
                        <td>
                            <?php if ($document['type'] != 'ccd') {
                                if ($document['type'] == 'url') {
                                    echo '<a href="' . $document['local_file_name'] . '" target="_blank">';
                                } else {
                                    echo '<a href="' . CC_DOC_PATH . '/' . $document['local_file_name'] . '" download="' . $document['name'] . '">';
                                }
                            }
                            ?>
                            <?php echo htmlspecialchars(rawurldecode($document['name']), ENT_QUOTES, 'UTF-8'); ?> </td>
                        <?php if ($document['type'] != 'ccd') {
                            echo  '</a>';
                        } ?>

                        <?php if (isset($search)) { ?>
                            <td>
                                <?php if ($document['type'] != 'ccd') {
                                    if ($document['type'] == 'url') {
                                        echo '<a href="' . $document['local_file_name'] . ' target="_blank">';
                                    } else {
                                        echo '<a href="' . CC_DOC_PATH . '/' . $document['local_file_name'] . '" download="' . $document['name'] . '">';
                                    }
                                }
                                ?>
                                <?php echo htmlspecialchars(rawurldecode($document['folder']), ENT_QUOTES, 'UTF-8'); ?></td>
                            <?php if ($document['type'] != 'ccd') {
                                echo  '</a>';
                            } ?>
                        <?php } ?>
                        <td>
                            <?php if ($document['type'] != 'ccd') {
                                if ($document['type'] == 'url') {
                                    echo '<a href="' . $document['local_file_name'] . ' target="_blank">';
                                } else {
                                    echo '<a href="' . CC_DOC_PATH . '/' . $document['local_file_name'] . '" download="' . $document['name'] . '">';
                                }
                            }
                            ?>
                            <?php echo $date; ?></td>
                        <?php if ($document['type'] != 'ccd') {
                            echo  '</a>';
                        } ?>
                        <td>
                            <?php if ($document['type'] != 'ccd') {
                                if ($document['type'] == 'url') {
                                    echo '<a href="' . $document['local_file_name'] . '" target="_blank">';
                                } else {
                                    echo '<a href="' . CC_DOC_PATH . '/' . $document['local_file_name'] . '" download="' . $document['name'] . '">';
                                }
                            }
                            ?>
                            <?php echo $user; ?>
                            <?php if ($document['type'] != 'ccd') {
                                echo  '</a>';
                            } ?>
                        </td>
                    </tr>

                <?php

                }
                ?>

            </tbody>
        </table>
        </div>
    </div>