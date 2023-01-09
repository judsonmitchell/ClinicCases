<div class="user_display ui-widget ui-corner-bottom user_widget" tabindex="1">

</div>
<?php if ($update == false) {
?>
    <div class="case_documents_toolbar">
        <div>

            <div class="form__control search">
                <input id="caseContactsSearch-<?php echo $case_id ?>" data-caseid="<?php echo $case_id ?>" type="text" class="contacts_search" placeholder=" " value="<?php if (isset($s)) {
                                                                                                                                                                            echo $s;
                                                                                                                                                                        } ?>">
                <label for="caseContactsSearch-<?php echo $case_id ?>">Search Contacts <span><img src="html/ico/search.png" /></span></label>
            </div>

            <button class="search_clear cases_contacts_search_clear" data-caseid="4">×</button>

        </div>

        <div class="case_documents_toolbar--right">
            <button class="button--secondary print-button">
                <img src="html/ico/printer.svg" alt="Print Icon"> <span>&nbsp;Print</span>
            </button>
            <button class="button--primary new_contact">
                + <span>&nbsp;New Contact</span>
            </button>
        </div>
    </div>
    <div class="case_detail_panel_contacts">
    <?php
}
    ?>




    <?php
    foreach ($contacts as $contact) {
        echo "
            <div class='contact' data-id = '$contact[id]'>
                <div class='contact_bar'>
                    <div class = 'csecontact_bar_left'>";

        if (empty($contact['first_name']) and empty($contact['last_name'])) {
            echo "<h4>" . htmlspecialchars($contact['organization'], ENT_QUOTES, 'UTF-8') . "</h4>";
        } else {
            echo "<h4><span class='cnt_first_name'>" . htmlspecialchars($contact['first_name'], ENT_QUOTES, 'UTF-8') .
                "</span> <span class='cnt_last_name'>" . htmlspecialchars($contact['last_name'], ENT_QUOTES, 'UTF-8') . "</span></h4>";
        }

        echo "<h5><span class='cnt_type'>" . htmlspecialchars($contact['type'], ENT_QUOTES, 'UTF-8')  . "</span></h5></div>
                    <div class = 'csecontact_bar_right'>";

        if ($_SESSION['permissions']['edit_contacts'] == '1') {
            echo "<a href='#' class='contact_edit'>Edit</a> ";
        }

        if ($_SESSION['permissions']['delete_contacts'] == '1') {
            echo "<a href='#' class='contact_delete'>Delete</a>";
        }

        echo "
                    </div>
                </div>

                <div class='contact_body'>";

        if ($contact['organization']) {
            echo "<p><label>Organization: </label><span class='cnt_organization'>" .
                htmlspecialchars($contact['organization'], ENT_QUOTES, 'UTF-8') . "</span></p>";
        }

        if ($contact['phone']) {
            $phones = json_decode($contact['phone'], true);

            foreach ($phones as $key => $value) {
                if (!empty($value)) {
                    echo "<p class='contact_phone_group'><label>Phone (<span class='contact_phone_type'>$key</span>)</label>
                                <span  class='contact_phone_value'>" . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . " </span></p>";
                }
            }
        }

        if ($contact['email']) {
            $emails = json_decode($contact['email'], true);

            foreach ($emails as $key => $value) {
                if (!empty($value)) {
                    echo "<p class='contact_email_group'><label>Email (<span class='contact_email_type'>$key</span>)</label>" .
                        "<a href='mailto:" . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . "' target='_blank'>
                                <span class='contact_email_value'>" . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . "</span></a></p>";
                }
            }
        }

        if (empty($contact['organization']) and empty($contact['phone']) and empty($contact['email'])) {
            echo "<p><label>....</label></p>";
        }



        if ($contact['address']) {
            echo "<p><label>Address:</label><span class='cnt_address'>" . htmlspecialchars($contact['address'], ENT_QUOTES, 'UTF-8') . "</span><br />
                    <span class='cnt_city'>" . htmlspecialchars($contact['city'], ENT_QUOTES, 'UTF-8')  . "</span> <span class='cnt_state'>" .
                htmlspecialchars($contact['state'], ENT_QUOTES, 'UTF-8') . "</span> <span class='cnt_zip'>" .
                htmlspecialchars($contact['zip'], ENT_QUOTES, 'UTF-8')  . "</span></p>";
        }
        if ($contact['url']) {
            echo "<p><label>Website:</label><a href='" . htmlspecialchars($contact['url'], ENT_QUOTES, 'UTF-8') .
                "' target='_blank'><span class='cnt_url'>" . htmlspecialchars($contact['url'], ENT_QUOTES, 'UTF-8') . "</span></a>";
        }
        if ($contact['notes']) {
            echo "<p><label>Notes:</label><span class='cnt_notes'>" . nl2br(htmlspecialchars($contact['notes'], ENT_QUOTES, 'UTF-8')) . "</span></p>";
        }

        echo "</div>

            </div>";
    }

    if (empty($contacts)) {
        if (isset($q)) {
            echo "<p>No contacts found matching <i>$q</i></p>";
        } else {
            echo "<p>No contacts in this case</p>";
        }
    }
    ?>
    <?php if ($update == false) {
    ?>
    </div>
<?php
    }
?>