<div><?php echo $title; ?></div>
<div>
    <form method="post" action="<?php echo base_url(); ?>sendmail/dosendmail">
        <table>
            <tr>
                <td>
                    <input type="submit" value="Send" />
                </td>
            </tr>
        </table>
    </form>
</div>