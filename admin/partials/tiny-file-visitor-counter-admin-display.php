<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://webklex.com
 * @since      1.0.0
 *
 * @package    Tiny_File_Visitor_Counter
 * @subpackage Tiny_File_Visitor_Counter/admin/partials
 */
?>

<div class="wrap">

    <h1><?=esc_html(get_admin_page_title()); ?></h1>
    <h2 class="nav-tab-wrapper">
        <a href="#settings" class="nav-tab nav-tab-active"><?=__('Settings', $this->plugin_name) ?></a>
        <a href="#usage" class="nav-tab"><?=__('Usage', $this->plugin_name); ?></a>
        <a href="#help" class="nav-tab"><?=__('Help', $this->plugin_name); ?></a>
    </h2>

    <form method="post" name="tiny_file_visitor_counter_options" action="options.php"  id="settings">
        <?php
        //Grab all options
        $options = get_option($this->plugin_name);

        /* Input values
         * */
        $valid = [];
        //Checkboxes
        $valid['backup'] = (isset($options['backup']) && !empty($options['backup'])) ? $options['backup'] : 1;
        $valid['live'] = (isset($options['live']) && !empty($options['live'])) ? $options['live'] : 0;
        $valid['online'] = (isset($options['online']) && !empty($options['online'])) ? $options['online']: 0;
        $valid['lastDay'] = (isset($options['lastDay']) && !empty($options['lastDay'])) ? $options['lastDay'] : 1;
        $valid['lastWeek'] = (isset($options['lastWeek']) && !empty($options['lastWeek'])) ? $options['lastWeek'] : 0;
        $valid['lastMonth'] = (isset($options['lastMonth']) && !empty($options['lastMonth'])) ? $options['lastMonth'] : 0;
        $valid['lastYear'] = (isset($options['lastYear']) && !empty($options['lastYear'])) ? $options['lastYear'] : 0;

        //labels
        $valid['onlineLabel'] = (isset($options['onlineLabel']) && !empty($options['onlineLabel']))?$options['onlineLabel']:'Now online';
        $valid['lastDayLabel'] = (isset($options['lastDayLabel']) && !empty($options['lastDayLabel']))?$options['lastDayLabel']:'Last day';
        $valid['lastWeekLabel'] = (isset($options['lastWeekLabel']) && !empty($options['lastWeekLabel']))?$options['lastWeekLabel']:'Last week';
        $valid['lastMonthLabel'] = (isset($options['lastMonthLabel']) && !empty($options['lastMonthLabel']))?$options['lastMonthLabel']:'Last month';
        $valid['lastYearLabel'] = (isset($options['lastYearLabel']) && !empty($options['lastYearLabel']))?$options['lastYearLabel']:'Last year';

        //URLs
        $valid['api'] = (isset($options['api']) && !empty($options['api']))?$options['api']:'http://ip-api.com/json';
        $valid['db'] = (isset($options['db']) && !empty($options['db']))?$options['db']:dirname(dirname(__DIR__)) .'/lib/database';
        $valid['timeout'] = (isset($options['timeout']) && !empty($options['timeout']))?$options['timeout']:5000;
        $valid['countTime'] = (isset($options['countTime']) && !empty($options['countTime']))?$options['countTime']:15;
        ?>

        <?php
        settings_fields($this->plugin_name);
        do_settings_sections($this->plugin_name);
        ?>
        <table class="widefat">
            <tr class="alternate">
                <td class="row-title" colspan="4">
                    <h2>General Settings</h2>
                </td>
            </tr>
            <tr class="alternate">
                <td class="row-title short">
                    <label for="<?=$this->plugin_name?>-api">
                        <?=__('Remote API', $this->plugin_name); ?>
                    </label>
                </td>
                <td colspan="3">
                    <input type="text"
                           class="large-text"
                           id="<?=$this->plugin_name?>-api"
                           name="<?=$this->plugin_name?>[api]"
                           value="<?=$valid['api']?>"
                    />
                </td>
            </tr>
            <tr class="">
                <td class="row-title">
                    <label for="<?=$this->plugin_name?>-db">
                        <?=__('Database file location', $this->plugin_name); ?>
                    </label>
                </td>
                <td colspan="3">
                    <input type="text"
                           class="large-text"
                           id="<?=$this->plugin_name?>-db"
                           name="<?=$this->plugin_name?>[db]"
                           value="<?=$valid['db']?>"
                    />
                </td>
            </tr>
            <tr class="alternate">
                <td class="row-title">
                    <label for="<?=$this->plugin_name?>-live">
                        <?=__('Live Statistics', $this->plugin_name); ?>
                    </label>
                </td>
                <td>
                    <input type="checkbox"
                           id="<?=$this->plugin_name?>-live"
                           name="<?=$this->plugin_name?>[live]"
                           value="1"
                            <?=checked($valid['live'], 1)?>
                    />
                </td>
                <td class="row-title xs">
                    <label for="<?=$this->plugin_name?>-timeout">
                        <?=__('Timeout', $this->plugin_name); ?>
                    </label>
                </td>
                <td>
                    <input type="text"
                           class="all-options"
                           id="<?=$this->plugin_name?>-timeout"
                           name="<?=$this->plugin_name?>[timeout]"
                           value="<?=$valid['timeout']?>"
                    /> Milliseconds
                </td>
            </tr>
            <tr class="">
                <td class="row-title">
                    <label for="<?=$this->plugin_name?>-countTime">
                        <?=__('Recount after x Minutes', $this->plugin_name); ?>
                    </label>
                </td>
                <td colspan="3">
                    <input type="text"
                           class="all-options"
                           id="<?=$this->plugin_name?>-countTime"
                           name="<?=$this->plugin_name?>[countTime]"
                           value="<?=$valid['countTime']?>"
                    /> Minutes
                </td>
            </tr>
            <tr class="alternate">
                <td class="row-title">
                    <label for="<?=$this->plugin_name?>-backup">
                        <?=__('Enable File backups', $this->plugin_name); ?>
                    </label>
                </td>
                <td>
                    <input type="checkbox"
                           id="<?=$this->plugin_name?>-backup"
                           name="<?=$this->plugin_name?>[backup]"
                           value="1"
                            <?=checked($valid['backup'], 1)?>
                    />
                </td>
            </tr>
            <!--
            <tr class="">
                <td class="row-title" colspan="4">
                    <h2>Counters</h2>
                </td>
            </tr>
            <tr class="alternate">
                <td class="row-title">
                    <label for="<?=$this->plugin_name?>-online">
                        <?=__('Enable "Now Online" statistics', $this->plugin_name); ?>
                    </label>
                </td>
                <td>
                    <input type="checkbox"
                           id="<?=$this->plugin_name?>-online"
                           name="<?=$this->plugin_name?>[online]"
                           value="1"
                            <?=checked($valid['online'], 1)?>
                    />
                </td>
                <td class="row-title xs">
                    <label for="<?=$this->plugin_name?>-onlineLabel">
                        <?=__('Label', $this->plugin_name); ?>
                    </label>
                </td>
                <td>
                    <input type="text"
                           class="all-options"
                           id="<?=$this->plugin_name?>-onlineLabel"
                           name="<?=$this->plugin_name?>[onlineLabel]"
                           value="<?=$valid['onlineLabel']?>"
                    />
                </td>
            </tr>
            <tr class="">
                <td class="row-title">
                    <label for="<?=$this->plugin_name?>-lastDay">
                        <?=__('Enable "Last day" statistics', $this->plugin_name); ?>
                    </label>
                </td>
                <td>
                    <input type="checkbox"
                           id="<?=$this->plugin_name?>-lastDay"
                           name="<?=$this->plugin_name?>[lastDay]"
                           value="1"
                            <?=checked($valid['lastDay'], 1)?>
                    />
                </td>
                <td class="row-title xs">
                    <label for="<?=$this->plugin_name?>-lastDayLabel">
                        <?=__('Label', $this->plugin_name); ?>
                    </label>
                </td>
                <td>
                    <input type="text"
                           class="all-options"
                           id="<?=$this->plugin_name?>-lastDayLabel"
                           name="<?=$this->plugin_name?>[lastDayLabel]"
                           value="<?=$valid['lastDayLabel']?>"
                    />
                </td>
            </tr>
            <tr class="alternate">
                <td class="row-title">
                    <label for="<?=$this->plugin_name?>-lastWeek">
                        <?=__('Enable "Last week" statistics', $this->plugin_name); ?>
                    </label>
                </td>
                <td>
                    <input type="checkbox"
                           id="<?=$this->plugin_name?>-lastWeek"
                           name="<?=$this->plugin_name?>[lastWeek]"
                           value="1"
                            <?=checked($valid['lastWeek'], 1)?>
                    />
                </td>
                <td class="row-title xs">
                    <label for="<?=$this->plugin_name?>-lastWeekLabel">
                        <?=__('Label', $this->plugin_name); ?>
                    </label>
                </td>
                <td>
                    <input type="text"
                           class="all-options"
                           id="<?=$this->plugin_name?>-lastWeekLabel"
                           name="<?=$this->plugin_name?>[lastWeekLabel]"
                           value="<?=$valid['lastWeekLabel']?>"
                    />
                </td>
            </tr>
            <tr class="">
                <td class="row-title">
                    <label for="<?=$this->plugin_name?>-lastMonth">
                        <?=__('Enable "Last month" statistics', $this->plugin_name); ?>
                    </label>
                </td>
                <td>
                    <input type="checkbox"
                           id="<?=$this->plugin_name?>-lastMonth"
                           name="<?=$this->plugin_name?>[lastMonth]"
                           value="1"
                            <?=checked($valid['lastMonth'], 1)?>
                    />
                </td>
                <td class="row-title xs">
                    <label for="<?=$this->plugin_name?>-lastMonthLabel">
                        <?=__('Label', $this->plugin_name); ?>
                    </label>
                </td>
                <td>
                    <input type="text"
                           class="all-options"
                           id="<?=$this->plugin_name?>-lastMonthLabel"
                           name="<?=$this->plugin_name?>[lastMonthLabel]"
                           value="<?=$valid['lastMonthLabel']?>"
                    />
                </td>
            </tr>
            <tr class="alternate">
                <td class="row-title">
                    <label for="<?=$this->plugin_name?>-lastYear">
                        <?=__('Enable "Last year" statistics', $this->plugin_name); ?>
                    </label>
                </td>
                <td>
                    <input type="checkbox"
                           id="<?=$this->plugin_name?>-lastYear"
                           name="<?=$this->plugin_name?>[lastYear]"
                           value="1"
                            <?=checked($valid['lastYear'], 1)?>
                    />
                </td>
                <td class="row-title xs">
                    <label for="<?=$this->plugin_name?>-lastYearLabel">
                        <?=__('Label', $this->plugin_name); ?>
                    </label>
                </td>
                <td>
                    <input type="text"
                           class="all-options"
                           id="<?=$this->plugin_name?>-lastYearLabel"
                           name="<?=$this->plugin_name?>[lastYearLabel]"
                           value="<?=$valid['lastYearLabel']?>"
                    />
                </td>
            </tr>
            -->
        </table>

        <?php submit_button(__('Save all changes'), 'primary','submit', TRUE); ?>

    </form>

    <div class="wp-tab-panel" id="usage">
        <h2>Usage</h2>
        <xmp>
Now Online:  <js-counter-online></js-counter-online>
Last day:    <js-counter-day></js-counter-day>
Last week:   <js-counter-day7></js-counter-day7>
Last month:  <js-counter-day30></js-counter-day30>
Last year:   <js-counter-year></js-counter-year>
        </xmp>
    </div>

    <br />
    <br />
    <br />

    <table class="widefat" id="help">
        <tr class="alternate">
            <td class="row-title" colspan="4">
                <h2>Help</h2>
            </td>
        </tr>
        <tr class="alternate">
            <td class="row-title xs">
                Email:
            </td>
            <td colspan="3">
                info@webklex.com
            </td>
        </tr>
    </table>

</div>