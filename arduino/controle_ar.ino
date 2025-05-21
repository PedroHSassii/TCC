#include <IRremote.h>
#include <DHT.h>

#define DHTPIN 4 // Pino do sensor de temperatura
#define DHTTYPE DHT22 // Tipo do sensor
DHT dht(DHTPIN, DHTTYPE);

IRsend irsend; // Para enviar sinais IR
IRrecv irrecv(2); // Pino do receptor IR
decode_results results;

void setup() {
  Serial.begin(115200);
  dht.begin();
  irrecv.enableIRIn(); // Inicia o receptor IR
}

void loop() {
  // Leitura da temperatura
  float t = dht.readTemperature();
  if (isnan(t)) {
    Serial.println("Falha na leitura do sensor!");
  } else {
    Serial.print("Temperatura: ");
    Serial.println(t);
  }

  // Recep��o de sinais IR
  if (irrecv.decode(&results)) {
    Serial.print("C�digo IR recebido: ");
    Serial.println(results.value, HEX);
    irrecv.resume(); // Prepara para o pr�ximo c�digo
  }

  // Exemplo de envio de sinal IR para ligar o ar-condicionado
  // irsend.sendNEC(0x20DF10EF, 32); // C�digo do controle remoto
  delay(2000); // Delay para evitar sobrecarga
}
