void alarmIqamah() {
  for (int i = 0; i <= 7; i++) {
    digitalWrite(LED_IQOMAH, HIGH);
    digitalWrite(BUZZER, HIGH);
    delay(1000);
    digitalWrite(LED_IQOMAH, LOW);
    digitalWrite(BUZZER, LOW);
    delay(1000);
  }
}

