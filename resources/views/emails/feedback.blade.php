<table  width="100%">
    <tbody>
        <tr>
            <td>
                <table cellspacing="12" cellpadding="2" bgcolor="#FFFFFF" align="center" width="600" border="0">
                    <tbody>
                        <tr>
                            <td style="padding:12px;border-bottom-color:rgb(75,121,147);border-bottom-width:1px;border-bottom-style:solid" bgcolor="#ffffff">
                                <table cellspacing="0" cellpadding="0" width="100%" border="0">
                                    <tbody>
                                        <tr>
                                           <td valign="middle" width="20%"><img src="{{asset('logo/') }}/{{trim($logo)}}" alt="Logo" height="54" class=""></td>
                                            <td valign="middle" align="right" width="54%"></td>
                                            <td valign="middle" width="26%"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" width="580px">
                                <table cellpadding="10" width="580px" border="0">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <p align="left"><font size="2" face="Arial, Helvetica, sans-serif">
                                      Dear <b>{{$name}}</b>
                                      <br>
                                      <br>You recently requested a password reset for your Panel. To complete the process, click the link below.
<br>

                                       <br><a href="{{ $feedback }}">Verify Link</a></b>

                                      <br><br>                                    

                                   </font>
                                                </p>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>