<?php
/**
 * Biz Calendar 管理画面UI
 *
 * @package BizCalendar
 */

defined( 'ABSPATH' ) || exit;

/**
 * 管理画面の設定フィールドを登録・描画するクラス
 */
class AdminUi {
	/**
	 * オプション名
	 *
	 * @var string
	 */
	public $option_name;

	/**
	 * プラグインファイルパス
	 *
	 * @var string
	 */
	public $file_path;

	/**
	 * コンストラクタ
	 *
	 * @param string $file プラグインファイルパス.
	 */
	public function __construct( $file ) {
		$this->option_name = BC::OPTIONS;
		$this->file_path   = $file;
		$this->set_ui();
	}

	/**
	 * 設定フィールドを登録する
	 */
	public function set_ui() {
		register_setting( $this->option_name, $this->option_name, array( $this, 'validate' ) );
		add_settings_section( 'fixed_holiday', '定休日', array( $this, 'text_fixed_holiday' ), $this->file_path );
		add_settings_field( 'id_holiday_title', '定休日の説明', array( $this, 'setting_holiday_title' ), $this->file_path, 'fixed_holiday' );
		add_settings_field( 'id_chk_sun', '日曜日', array( $this, 'setting_chk_sun' ), $this->file_path, 'fixed_holiday' );
		add_settings_field( 'id_chk_mon', '月曜日', array( $this, 'setting_chk_mon' ), $this->file_path, 'fixed_holiday' );
		add_settings_field( 'id_chk_tue', '火曜日', array( $this, 'setting_chk_tue' ), $this->file_path, 'fixed_holiday' );
		add_settings_field( 'id_chk_wed', '水曜日', array( $this, 'setting_chk_wed' ), $this->file_path, 'fixed_holiday' );
		add_settings_field( 'id_chk_thu', '木曜日', array( $this, 'setting_chk_thu' ), $this->file_path, 'fixed_holiday' );
		add_settings_field( 'id_chk_fri', '金曜日', array( $this, 'setting_chk_fri' ), $this->file_path, 'fixed_holiday' );
		add_settings_field( 'id_chk_sat', '土曜日', array( $this, 'setting_chk_sat' ), $this->file_path, 'fixed_holiday' );
		add_settings_field( 'id_chk_holiday', '祝日を定休日にする', array( $this, 'setting_chk_holiday' ), $this->file_path, 'fixed_holiday' );

		add_settings_section( 'temp_holiday', '臨時休営業日', array( $this, 'text_temp_holiday' ), $this->file_path );
		add_settings_field( 'id_temp_holidays', '臨時休業日', array( $this, 'setting_temp_holidays' ), $this->file_path, 'temp_holiday' );
		add_settings_field( 'id_temp_weekdays', '臨時営業日', array( $this, 'setting_temp_weekdays' ), $this->file_path, 'temp_holiday' );

		add_settings_section( 'eventday', 'イベント', array( $this, 'text_eventday' ), $this->file_path );
		add_settings_field( 'id_eventday_title', 'イベントの説明', array( $this, 'setting_eventday_title' ), $this->file_path, 'eventday' );
		add_settings_field( 'id_eventday_url', 'イベントのurl', array( $this, 'setting_eventday_url' ), $this->file_path, 'eventday' );
		add_settings_field( 'id_eventdays', 'イベント日', array( $this, 'setting_eventdays' ), $this->file_path, 'eventday' );

		add_settings_section( 'monthlimit', '月送り制限', array( $this, 'text_monthlimit' ), $this->file_path );
		add_settings_field( 'id_monthlimit', '月送り制限設定', array( $this, 'setting_monthlimit' ), $this->file_path, 'monthlimit' );
		add_settings_field( 'id_nextmonthlimit', '次の月', array( $this, 'setting_nextmonthlimit' ), $this->file_path, 'monthlimit' );
		add_settings_field( 'id_prevmonthlimit', '前の月', array( $this, 'setting_prevmonthlimit' ), $this->file_path, 'monthlimit' );
	}

	/**
	 * 入力値をサニタイズして返す
	 *
	 * @param array $input フォームからの入力値.
	 * @return array サニタイズ済みの入力値。
	 */
	public function validate( $input ) {
		$input['holiday_title']  = sanitize_text_field( $input['holiday_title'] );
		$input['eventday_title'] = sanitize_text_field( $input['eventday_title'] );
		$input['eventday_url']   = esc_url_raw( $input['eventday_url'] );
		$input['temp_holidays']  = sanitize_textarea_field( $input['temp_holidays'] );
		$input['temp_weekdays']  = sanitize_textarea_field( $input['temp_weekdays'] );
		$input['eventdays']      = sanitize_textarea_field( $input['eventdays'] );
		return $input;
	}

	/**
	 * 定休日セクションの説明テキストを出力する
	 */
	public function text_fixed_holiday() {
		echo '<p>定休日として設定する曜日をチェックします。「祝日を定休日にする」には祝日ファイルの登録が必要です</p>';
	}

	/**
	 * 臨時休営業日セクションの説明テキストを出力する
	 */
	public function text_temp_holiday() {
		echo '<p>臨時営業日・休業日を設定します。<br>YYYY-MM-DD (例 2001-01-01)の形式で登録します。複数登録する場合は改行してください。登録できる件数の上限はありません。</p>';
	}

	/**
	 * イベントセクションの説明テキストを出力する
	 */
	public function text_eventday() {
		echo '<p>イベントの説明、url、日にちを登録します。<br>イベント日は、YYYY-MM-DD (例 2001-01-01)の形式で登録します。複数登録する場合は改行してください。登録できる件数の上限はありません。</p>';
	}

	/**
	 * 月送り制限セクションの説明テキストを出力する（未使用）
	 */
	public function text_monthlimit() {
	}

	/**
	 * 日曜日チェックボックスを出力する
	 */
	public function setting_chk_sun() {
		$this->setting_chk( 'sun' );
	}

	/**
	 * 月曜日チェックボックスを出力する
	 */
	public function setting_chk_mon() {
		$this->setting_chk( 'mon' );
	}

	/**
	 * 火曜日チェックボックスを出力する
	 */
	public function setting_chk_tue() {
		$this->setting_chk( 'tue' );
	}

	/**
	 * 水曜日チェックボックスを出力する
	 */
	public function setting_chk_wed() {
		$this->setting_chk( 'wed' );
	}

	/**
	 * 木曜日チェックボックスを出力する
	 */
	public function setting_chk_thu() {
		$this->setting_chk( 'thu' );
	}

	/**
	 * 金曜日チェックボックスを出力する
	 */
	public function setting_chk_fri() {
		$this->setting_chk( 'fri' );
	}

	/**
	 * 土曜日チェックボックスを出力する
	 */
	public function setting_chk_sat() {
		$this->setting_chk( 'sat' );
	}

	/**
	 * 祝日チェックボックスを出力する
	 */
	public function setting_chk_holiday() {
		$this->setting_chk( 'holiday' );
	}

	/**
	 * チェックボックスフィールドを出力する
	 *
	 * @param string $id オプションキー名.
	 */
	public function setting_chk( $id ) {
		$options = get_option( $this->option_name );
		$checked = ( isset( $options[ $id ] ) && $options[ $id ] ) ? ' checked="checked" ' : '';
		$name    = $this->option_name . '[' . $id . ']';
		printf(
			'<input %s id="id_%s" name="%s" type="checkbox" />',
			esc_attr( $checked ),
			esc_attr( $id ),
			esc_attr( $name )
		);
	}

	/**
	 * テキスト入力フィールドを出力する
	 *
	 * @param string $name フィールド名.
	 * @param int    $size フィールドの表示幅.
	 */
	public function setting_inputtext( $name, $size ) {
		$options = get_option( $this->option_name );
		printf(
			'<input id="%s" name="bizcalendar_options[%s]" size="%d" type="text" value="%s" />',
			esc_attr( $name ),
			esc_attr( $name ),
			(int) $size,
			esc_attr( $options[ $name ] )
		);
	}

	/**
	 * 定休日タイトルフィールドを出力する
	 */
	public function setting_holiday_title() {
		$this->setting_inputtext( 'holiday_title', 40 );
	}

	/**
	 * イベントタイトルフィールドを出力する
	 */
	public function setting_eventday_title() {
		$this->setting_inputtext( 'eventday_title', 40 );
	}

	/**
	 * イベントURLフィールドを出力する
	 */
	public function setting_eventday_url() {
		$this->setting_inputtext( 'eventday_url', 60 );
	}

	/**
	 * テキストエリアフィールドを出力する
	 *
	 * @param string $name フィールド名.
	 */
	public function setting_textarea( $name ) {
		$options = get_option( $this->option_name );
		printf(
			'<textarea id="%s" name="bizcalendar_options[%s]" rows="7" cols="15">%s</textarea>',
			esc_attr( $name ),
			esc_attr( $name ),
			esc_textarea( $options[ $name ] )
		);
	}

	/**
	 * 臨時休業日テキストエリアを出力する
	 */
	public function setting_temp_holidays() {
		$this->setting_textarea( 'temp_holidays' );
	}

	/**
	 * 臨時営業日テキストエリアを出力する
	 */
	public function setting_temp_weekdays() {
		$this->setting_textarea( 'temp_weekdays' );
	}

	/**
	 * イベント日テキストエリアを出力する
	 */
	public function setting_eventdays() {
		$this->setting_textarea( 'eventdays' );
	}

	/**
	 * 月送り制限ラジオボタンを出力する
	 */
	public function setting_monthlimit() {
		$options = get_option( $this->option_name );
		$items   = array( '制限なし', '年内', '年度内', '指定' );
		foreach ( $items as $item ) {
			$checked = ( $options['month_limit'] === $item ) ? ' checked="checked" ' : '';
			printf(
				'<label><input %s value="%s" name="bizcalendar_options[month_limit]" type="radio" /> %s</label><br />',
				esc_attr( $checked ),
				esc_attr( $item ),
				esc_html( $item )
			);
		}
	}

	/**
	 * 翌月制限セレクトボックスを出力する
	 */
	public function setting_nextmonthlimit() {
		$options = get_option( $this->option_name );
		$items   = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12' );
		echo '<select id="nextmonthlimit" name="bizcalendar_options[nextmonthlimit]">';
		foreach ( $items as $item ) {
			$selected = ( $options['nextmonthlimit'] === $item ) ? 'selected="selected"' : '';
			printf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $item ),
				esc_attr( $selected ),
				esc_html( $item )
			);
		}
		echo '</select>';
		echo 'ヶ月先まで';
	}

	/**
	 * 前月制限セレクトボックスを出力する
	 */
	public function setting_prevmonthlimit() {
		$options = get_option( $this->option_name );
		$items   = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12' );
		echo '<select id="prevmonthlimit" name="bizcalendar_options[prevmonthlimit]">';
		foreach ( $items as $item ) {
			$selected = ( $options['prevmonthlimit'] === $item ) ? 'selected="selected"' : '';
			printf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $item ),
				esc_attr( $selected ),
				esc_html( $item )
			);
		}
		echo '</select>';
		echo 'ヶ月前まで';
	}
}
