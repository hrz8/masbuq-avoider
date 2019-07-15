void refreshNow() {
  waktuStart = masjid.waktuSekarang();
  hourNow = waktuStart.substring(0, 2).toInt();
  minuteNow = waktuStart.substring(3, 5).toInt();
  secondNow = waktuStart.substring(6, 8).toInt();
}

