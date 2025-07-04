#include <DHT.h>
#include <IRremote.h>
#include <WiFi.h>
#include <MySQL_Connection.h>
#include <MySQL_Cursor.h>

// Definições de constantes
const int DHTPIN = 2; // Pino onde o DHT11 está conectado
const int RECV_PIN = 3; // Pino onde o TSOP4838 está conectado
const int DHTTYPE = DHT11; // Tipo do sensor DHT
const int predio_id = 1; // ID do prédio (fixo)
const int numero_sala = 101; // Número da sala (fixo)

// Configurações do WiFi
const char* ssid = "SEU_SSID";
const char* password = "SUA_SENHA";

// Configurações do MySQL
IPAddress server_addr(192, 168, 1, 100); // Endereço IP do servidor MySQL
char user[] = "usuario"; // Usuário do MySQL
char password[] = "senha"; // Senha do MySQL

DHT dht(DHTPIN, DHTTYPE);
IRrecv irrecv(RECV_PIN);
decode_results results;
WiFiClient client;
MySQL_Connection conn((Client *)&client);

void setup() {
  Serial.begin(115200);
  dht.begin();
  irrecv.enableIRIn(); // Inicia o receptor IR
  
  // Conexão com WiFi
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Conectando ao WiFi...");
  }
  Serial.println("Conectado ao WiFi!");

  // Conexão com MySQL
  if (conn.connect(server_addr, 3306, user, password)) {
    Serial.println("Conectado ao MySQL!");
  } else {
    Serial.println("Falha na conexão com MySQL.");
  }
}

void loop() {
  // Leitura da temperatura e umidade
  float h = dht.readHumidity();
  float t = dht.readTemperature();

  // Verifica se a leitura falhou
  if (isnan(h) || isnan(t)) {
    Serial.println("Falha na leitura do DHT!");
    return;
  }

  // Atualiza os dados no banco de dados
  updateDatabase(t, h);

  // Aguarda 30 segundos antes da próxima leitura
  delay(30000);

  // Leitura do código IR
  if (irrecv.decode(&results)) {
    Serial.print("Código IR: ");
    Serial.println(results.value, HEX); // Exibe o código em hexadecimal

    // Armazena o código na tabela temporária
    storeIRCode(results.value);

    irrecv.resume(); // Recebe o próximo código
  }
}

void updateDatabase(float temperature, float humidity) {
  char query[128];
  sprintf(query, "UPDATE ambiente_status SET temperatura = %.2f, umidade = %.2f WHERE predio_id = %d AND numero_sala = %d", temperature, humidity, predio_id, numero_sala);
  
  MySQL_Cursor cur = MySQL_Cursor(&conn);
  cur.execute(query);
  Serial.println("Dados atualizados no banco de dados.");
}

void storeIRCode(unsigned long code) {
  char query[128];
  sprintf(query, "INSERT INTO tabela_temporaria (codigo_ir, predio_id, numero_sala) VALUES (0x%X, %d, %d)", code, predio_id, numero_sala);
  
  MySQL_Cursor cur = MySQL_Cursor(&conn);
  cur.execute(query);
  Serial.println("Código IR armazenado na tabela temporária.");
}
