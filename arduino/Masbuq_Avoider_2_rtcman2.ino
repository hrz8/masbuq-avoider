#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <HttpHandler.h>
#include <MasjidYabai.h>

const int id = 6;
const char* ssid = "AE2016";
const char* password = "cingdogan";
const char* host = "nurfakhrian.com/masbuq_avoider/";
String token = "KwpkiowUEo22";

HttpHandler server(host);
MasjidYabai masjid(id, host, token);

//SDA ke pin D2 Wemos, lalu SCL ke pin D1 Wemos
LiquidCrystal_I2C lcd(0x27, 16, 2);

#define PB_5M D5
#define PB_10M D6
#define PB_15M D7
#define PB_CD D3
#define LED_ADZAN D4
#define LED_IQOMAH D8
#define BUZZER D0

int iqomah_minute, iqomah_second = 0, set_iqomah_minute;
int max_menit = 15;
boolean f_pb_5m, f_pb_10m, f_pb_15m, f_pb_cd, f_pb_cd_berhenti, f_pb_diubah, f_masuk_waktu;
boolean f_cd_5m, f_cd_10m, f_cd_15m, f_alarm_10beep_selesai;
boolean alarmState;

String waktuStart, waktuNow, waktuNowDetik;
String subuh, dzuhur, ashar, maghrib, isya;
String azanSelanjutnya, waktuAzanSelanjutnya;
int hourNow, minuteNow, secondNow;
int count_beep = 0;

unsigned long waktu_sudah_masuk;
unsigned long pm1, pm2, pm3, pm4, pm5;

void setup() {
  Serial.begin(115200);
  pinMode(PB_5M, INPUT_PULLUP);
  pinMode(PB_10M, INPUT_PULLUP);
  pinMode(PB_15M, INPUT_PULLUP);
  pinMode(PB_CD, INPUT_PULLUP);
  pinMode(LED_ADZAN, OUTPUT);
  pinMode(LED_IQOMAH, OUTPUT);
  pinMode(BUZZER, OUTPUT);
  server.connect(ssid, password);
  lcd.begin();
  lcd.backlight();
  lcd.setCursor(0, 0);
  lcd.print(" MASBUQ AVOIDER");
  lcd.setCursor(0, 1);
  lcd.print("   YABAI TEAM");
  delay(2000);
  masjid.setSudahIqamah(0);
  masjid.resetWaktuMulaiIqamah();
  subuh = masjid.subuh();
  dzuhur = masjid.dzuhur();
  ashar = masjid.ashar();
  maghrib = masjid.maghrib();
  isya = masjid.isya();
  set_iqomah_minute = masjid.jarakAzanIqamah().toInt();
  iqomah_minute = set_iqomah_minute;
  azanSelanjutnya = masjid.namaAzanSelanjutnya();
  waktuAzanSelanjutnya = masjid.waktuAzanSelanjutnya();
  waktuStart = masjid.waktuSekarang();
  hourNow = waktuStart.substring(0, 2).toInt(); // 23:59:58
  minuteNow = waktuStart.substring(3, 5).toInt();
  secondNow = waktuStart.substring(6, 8).toInt();
  lcd.clear();
}

void loop() {
  // FLAG COUNTDOWN HANDLER
  if (digitalRead(PB_CD) == 0 && f_pb_cd == 0) {
    delay(100);
    if (millis() <= (waktu_sudah_masuk + (max_menit * 60000))) {
      masjid.mulaiCountdown();
      masjid.setSudahIqamah(0);
      refreshNow();
      f_pb_cd = 1; 
    }
  }

  // RTC
  if (millis() - pm4 >= 1000) {
    pm4 = millis();
    waktuNowDetik = RTC();
    waktuNow = waktuNowDetik.substring(0, 5);
  }

  // COUNTDOWN PB NORMAL
  if (f_pb_cd == 0) {
    
    if (f_pb_cd_berhenti == 1) {
      lcd.setCursor(0, 0);
      lcd.print("WAKTUNYA  IQAMAH");
      lcd.setCursor(0, 1);
      lcd.print("                ");
      masjid.setSudahIqamah(1);
      alarmIqamah();
      refreshNow();
      lcd.clear();
      f_pb_cd_berhenti = 0;
    }
    if (f_pb_diubah == 1) {
      lcd.clear();
      f_pb_diubah = 0;
    }

    // REFRESH JADWAL IN BEGINNING OF THE DAY
    if (waktuNow == "00:01") {
      refreshJadwal();
    }
    // REFRESH TIMENOW EVERY 1.5 hour
    if (millis() - pm5 >= 5400000) {
      pm5 = millis();
      refreshNow();
    }

    // MASUK WAKTU
    if (waktuNow == subuh || waktuNow == dzuhur || waktuNow == ashar || waktuNow == maghrib || waktuNow == isya) {
      if (f_masuk_waktu == 0) {
        masjid.setSudahIqamah(0);
        f_masuk_waktu = 1;
      }
      waktu_sudah_masuk = millis();
      if (millis() - pm2 >= 1000 && count_beep < 10) {
        pm2 = millis();
        alarmState = !alarmState;
        if (alarmState == 0) {
          count_beep++; 
        }
      }
      digitalWrite(LED_ADZAN, alarmState);
      digitalWrite(BUZZER, alarmState);
      lcd.setCursor(0, 0);
      lcd.print("  MASUK WAKTU   ");
      lcd.setCursor(0, 1);
      if (waktuNow == subuh) {
        lcd.print("     SUBUH      ");
      }
      if (waktuNow == dzuhur) {
        lcd.print("     DZUHUR     ");
      }
      if (waktuNow == ashar) {
        lcd.print("     ASHAR      ");
      }
      if (waktuNow == maghrib) {
        lcd.print("    MAGHRIB     ");
      }
      if (waktuNow == isya) {
        lcd.print("      ISYA      ");
      }
      f_alarm_10beep_selesai = 1;
    }
    // BELUM MASUK WAKTU
    else {
      if (f_alarm_10beep_selesai == 1) {
        lcd.clear();
        refreshSelanjutnya();
        count_beep = 0;
        f_masuk_waktu = 0;
        f_alarm_10beep_selesai = 0;
      }
      digitalWrite(LED_ADZAN, LOW);
      
      if (azanSelanjutnya == "ISYA") {
        lcd.setCursor(3, 0);
      }
      else {
        lcd.setCursor(2, 0);
      }
      lcd.print(azanSelanjutnya);
      lcd.print(" ");
      lcd.print(waktuAzanSelanjutnya);
      lcd.setCursor(0, 1);
      lcd.print("A-I:");
      if (set_iqomah_minute == 5) {
        lcd.print(" ");
      }
      lcd.print(set_iqomah_minute);
      lcd.print("m ");
      lcd.print(waktuNowDetik);
    }

    // SET JARAK
    if (digitalRead(PB_5M) == 0 && f_pb_5m == 0 ||
        digitalRead(PB_10M) == 0 && f_pb_10m == 0 ||
        digitalRead(PB_15M) == 0 && f_pb_15m == 0) {
      int jar;
      if (digitalRead(PB_5M) == 0 && f_pb_5m == 0) {
        delay(100);
        f_cd_5m = 1;
        jar = 5;
        f_pb_diubah = 1;
        f_pb_10m = 0;
        f_pb_15m = 0;
        f_pb_5m = 1;
      }
      if (digitalRead(PB_10M) == 0 && f_pb_10m == 0) {
        delay(100);
        f_cd_10m = 1;
        jar = 10;
        f_pb_diubah = 1;
        f_pb_5m = 0;
        f_pb_15m = 0;
        f_pb_10m = 1;
      }
      if (digitalRead(PB_15M) == 0 && f_pb_15m == 0) {
        delay(100);
        f_cd_15m = 1;
        jar = 15;
        f_pb_diubah = 1;
        f_pb_5m = 0;
        f_pb_10m = 0;
        f_pb_15m = 1;
      }
      set_iqomah_minute = jar;
      iqomah_minute = set_iqomah_minute;
      masjid.setJarakAzanIqamah(jar);
      refreshNow();
      lcd.clear();
      lcd.setCursor(0, 0);
      lcd.print("BERHASIL DIUBAH");
      lcd.setCursor(0, 1);
      lcd.print("JARAK = ");
      lcd.print(jar);
      lcd.print(" menit");
      delay(2000);
    }
  }

  // COUNTDOWN
  if (f_pb_cd == 1) {
    if (digitalRead(PB_5M) == 0 && digitalRead(PB_10M) == 0 && digitalRead(PB_15M) == 0) {
      iqomah_second = 0;
      iqomah_minute = set_iqomah_minute;
      f_pb_5m = 0;
      f_pb_10m = 0;
      f_pb_15m = 0;
      masjid.stopCountdown();
      f_pb_cd = 0;
    }
    if (millis() - pm1 >= 1000) {
      pm1 = millis();
      lcd.clear();
      if (iqomah_second >= 0) {
        iqomah_second--;
      }
      if (iqomah_second < 0) {
        iqomah_second = 59;
        iqomah_minute--;
        if (iqomah_minute < 0) {
          iqomah_second = 0;
          iqomah_minute = set_iqomah_minute;
          f_pb_5m = 0;
          f_pb_10m = 0;
          f_pb_15m = 0;
          masjid.stopCountdown();
          f_pb_cd_berhenti = 1;
          f_pb_cd = 0;
        }
      }
      lcd.setCursor(5, 0);
      if (iqomah_minute < 10) {
        lcd.print("0");
      }
      lcd.print(iqomah_minute);
      lcd.print(":");
      if (iqomah_second < 10) {
        lcd.print("0");
      }
      lcd.print(iqomah_second);
      lcd.setCursor(1, 1);
      lcd.print("MENUJU  IQOMAH");
    }
  }

}

