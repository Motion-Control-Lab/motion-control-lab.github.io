;
;  dsPIC30F4012 REAL TIME CONTROL(TIMER INTERRUPT) TEST CODE
;

.include "p30f4012.inc"

; 設定
config __FOSC, CSW_FSCM_OFF & EC_PLL16	; ClockSW OFF, 外部Clock PLL16倍モード
config __FWDT, WDT_OFF					; WatchdogTimer OFF
config __FBORPOR, PBOR_ON & BORV20 & PWRT_16 & MCLR_EN
; 電源投入時自動リセット有効, 自動停止電圧2.0V, 電源投入タイマ16msec, MCLRピン有効
config __FGS, CODE_PROT_OFF				; CodeProtection OFF

; ルーチン
.global __T1Interrupt		; 割り込みルーチン
.global __reset				; 初期設定ルーチン

; dsPIC起動開始
.text
.org	0x000000
	goto	__reset			; 起動開始と共に初期設定を行う

; タイマ割り込みベクタ
__T1Interrupt:
	goto	T1Interrupt

; 初期設定ルーチン
__reset:
	; I/Oポート入出力設定
	mov		#0b0000000000000000,w0
	mov 	w0,TRISB
	mov 	#0b0000000000000000,w0
	mov 	w0,TRISC
	mov 	#0b0000000000000000,w0
	mov 	w0,TRISD
	mov 	#0b0000000000000000,w0
	mov 	w0,TRISE
	mov 	#0b0000000000000000,w0
	mov 	w0,TRISF
	
	; タイマ1割り込み設定
	clr		T1CON			; 制御レジスタ設定
	clr		TMR1			; タイマレジスタクリア
	mov		#0x0BB7, w0		; 周期レジスタの設定 0x0BB7=100μs (120MHz)
	mov		w0,		PR1		; 
	bset	IPC0,	#T1IP0	; タイマ1割り込み設定
	bclr	IPC0,	#T1IP1	; 優先度を
	bclr	IPC0,	#T1IP2	; 最高に設定
	bclr	IFS0,	#T1IF	; タイマ割り込みフラグをクリア
	bset	IEC0,	#T1IE	; タイマ割り込みを許可
	bset	T1CON,	#TON	; タイマ1始動
	
	
; タイマ割り込み待機ルーチン
; このルーチンはタイマ割り込みが発生するまで待機するルーチン
INT_WAIT:
	nop
	goto 	INT_WAIT		; 主ルーチン先頭に戻る

; タイマ割り込みルーチン
T1Interrupt:
	bclr	IEC0,	#T1IE	; タイマ割り込みを禁止
	call	PERIOD			; 周期ルーチン呼び出し
	bclr 	IFS0,	#T1IF	; タイマ割り込みフラグをリセット
	bset	IEC0,	#T1IE	; タイマ割り込みを許可
	retfie					; タイマ割り込み終了


; 周期ルーチン
; このルーチンは100μsごとに実行される。処理は3000step以内に納めること。(120MHz)
PERIOD:
	; ここに所望の制御コードを書く
	com.b	PORTE			; 100μs毎にPORTEを反転
	return

.end
