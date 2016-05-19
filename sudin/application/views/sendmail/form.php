<html>
<body>
    <table width='100%' cellpadding='10' cellspacing='0' style="border:5px solid silver">
        <tr height='140px'>
            <td colspan="3" align='center'>
                <img src="<?php echo base_url(); ?>media/images/logo.png" width="115" height="100"/>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table cellpadding='3'>
                    <tr>
                        <td colspan="3">{head}</td>
                    </tr>
                    <tr>
                        <td width='10%'>Email</td>
                        <td width='3%'>:</td>
                        <td style="font-weight: bold;">{email}</td>
                    </tr>
                    <tr>
                        <td>Username</td>
                        <td>:</td>
                        <td style="font-weight: bold;">{username}</td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            Untuk mengaktifkan Reseller anda klik link dibawah ini atau copy/paste link ke browser anda<br />
                            {link}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="border-top:5px solid silver">{signature}</td>
        </tr>
    </table>
</body>
</html>