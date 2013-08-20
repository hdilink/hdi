<?php require_once( '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'init.php' ); ?>
<div>
    <ul class="pool">
        <li>
            <a href="#">Vital Signs:</a>
            <ul>
                <li>
                    <form>
                        <table class="visible_table">
                            <tr>
                                <td>Temp:</td>
                                <td><?php Form::textbox('txt_tmp'); ?></td>
                            </tr>
                            <tr>
                                <td>BP:</td>
                                <td><?php Form::textbox('txt_bp'); ?></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><?php Form::button('btn_vs','Save Vitals'); ?></td>
                            </tr>
                        </table>
                    </form>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">Nurses Pool (3)</a>
            <ul>
                <li>
                    <a>[1] P1308/879</a>
                </li>
                <li>
                    <a>[4] P1202/475</a>
                </li>
                <li>
                    <a>[6] P1306/159</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">Doctors Pool (3)</a>
            <ul>
                <li>
                    <a>[2] P1308/279</a>
                </li>
                <li>
                    <a>[5] P1202/445</a>
                </li>
                <li>
                    <a>[7] P1306/136</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">Admissions (1)</a>
            <ul>
                <li>
                    <a>[3] P1308/059</a>
                </li>
            </ul>
        </li>
    </ul>
</div>