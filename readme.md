# Tema Joomla! - Single

Tema limpo com funções básicas para o Joomla!
*Testado a partir do Joomla! 3.x

## Separando arquivos por página
Para separar os arquivos é utilizada a pasta "html/custom", que possui o arquivo custom.php com funções primordiais do joomla > 3.0
Linha de ação recomendada para criar arquivo com php externo:
- Adicione um item de menu do tipo "Single Article" e inclua uma classe da página condizente ao conteúdo. [Ex.: contato]
- Crie um arquivo dentro da pasta "html/custom" com o nome utilizado na classe da página e utilize a extensão .block.php [Ex.: contato.block.php]
- Coloque no arquivo o conteúdo básico, localizado no tópico "Conteúdo básico do block".
- Agora o arquivo possui os recursos padrões do tema e todo o html será adicionado ao fim do artigo.
  
#### Mudar a chamada padrão do block
Caso queira que o conteúdo do seu .block seja exibido em alguma outra posição antes da chamada padrão basta fazer um require por ele seguindo o seguinte padrão:
```html
require_once(OVERRIDE_DIRECTORY.'custom/{nome do arquivo}.block.php');
Ex.: require_once(OVERRIDE_DIRECTORY.'custom/duvidas.block.php');
```
#### Exibir mensagem de alerta 
Para exibir basta gravar na session 'msg_to_display' o conteúdo a ser exibido e nas variáveis 'error' ou 'success' o valor booleano true na correspondente ao tipo de alerta
    
    
#### Conteúdo básico do block
```html
<?php
require_once ( JPATH_BASE . '/includes/defines.php' );
require_once ( JPATH_BASE . '/includes/framework.php' );
$mainframe = JFactory::getApplication('site');
$mainframe->initialise();
$user = JFactory::getUser();
$session = JFactory::getSession();
require_once ( 'custom.php' );
$customClass = new Custom();
$db = new DB();
?> 
```


## Comunicação com o Banco de Dados
### Instanciando o objeto DB
Para criar uma instância do objeto que possui as operações básicas de comunicação com o Banco de Dados deve-se incluir o arquivo "custom/custom.php" e iniciar a instância do banco em seguida. 
Ex.:
```html
require_once ( 'custom.php' );
$db = new DB();
```
*Obs.: Não é necessário iniciar a instância nos arquivos .block e no articles/default*
### Select
```html
$db->select ( string $values, string $table [, mixed $where , mixed $order , mixed $limit , mixed $group ]) : array
```
Essa função retorna um array associativo com os resultados da busca.
#### Parâmetros
*values*
  Os valores a serem retornados no select. Use null para retornar todos.
  
*table*
  A tabela onde será feito o select.
  
*where*
  Condição para pesquisa. [Opcional]
  
*order*
  A ordem de retorno dos dados. [Opcional]
  
*limit*
  Limitar o número de dados retornardos. [Opcional]
  
*group*
  Agrupar os dados a serem retornados. [Opcional]
  
### Insert
```html
$db->insert( string $table , array $values ) : void
```
Essa função insere os dados informados na tabela
#### Parâmetros
*table*
  Tabela no qual os valores serão inseridos
  
*values*
  Array contendo os valores a serem inseridose as chaves sendo as colunas a serem inseridos os valores, seguindo essa sintaxe:
  ``` array('id' => null, 'name' => $db->q("Nicholas Stefano")) ```
  
### Update
```html
$db->update( string $table , array $values, mixed $where) : void
```
Essa função atualiza os dados de uma tabela
#### Parâmetros
*table*
  Tabela no qual os valores serão atualizados
  
*values*
  Array de valores que deverão ser atualizados. Onde a key é a coluna e o value o novo valor a ser inserido. 
  Ex.: ``` array('idade' => 20, 'name' => $db->q("Nicholas S.")) ```
  
*where*
  Condição para as linhas que serão atualizadas
  
### Delete
```html
$db->delete(string $table, mixed $where): void
```
Essa função exclui os dados de uma tabela
#### Parâmetros
*table*
  Tabela no qual os valores serão excluídos
  
*where*
  Condição para as linhas que serão excluídas
