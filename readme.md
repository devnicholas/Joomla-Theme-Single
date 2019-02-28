# Tema Joomla! - Single

Tema limpo com funções básicas para o Joomla!
*Testado a partir do Joomla! > 3.0
## Instruções básicas:

#### Separando arquivos por página
Para separar os arquivos é utilizada a pasta "html/custom", que possui o arquivo custom.php com funções primordiais do joomla > 3.0
Linha de ação recomendada para criar arquivo com php externo:
- Adicione um item de menu do tipo "Single Article" e inclua uma classe da página condizente ao conteúdo. [Ex.: contato]
- Crie um arquivo dentro da pasta "html/custom" com o nome utilizado na classe da página e utilize a extensão .block.php [Ex.: contato.block.php]
- Coloque no arquivo o conteúdo básico, localizado no fim desse manual.
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
$user_fields = json_decode($customClass->getFields($user->id));
?> 
```
