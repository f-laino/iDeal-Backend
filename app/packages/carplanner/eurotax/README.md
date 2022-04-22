<p align="center"><img src="https://d260o8t6723rz8.cloudfront.net/carplanner/website/template/palma/assets/images/logo_2018_ao.png"></p>


## Eurotax

Il servizio permette di accedere ai dati tecnici ed le immagini del servizio fornito da Eurotax utilizzando il framework php Laravel.
## Installazione
Aggiungere al file ``composer.json`` i seguenti record (nel caso le chiavi fossereo gia presenti nel file aggiungere soltanto i valori )
```
"require" : {
  "carplanner/eurotax": "1.0"
},
"repositories": [
        {
            "type": "package",
            "package": {
                "name": "carplanner/eurotax",
                "version": "1.0",
                "source": {
                    "url": "https://github.com/kraw/eurotax.git",
                    "type": "git",
                    "reference": "master"
                }
            }
        }]
```

Aggiungi la seguente linea nel file `config/app.php`:

```
\CarPlanner\Eurotax\EurotaxServiceProvider::class,
```

Esegui il comando

```
php artisan vendor:publish
```
Aggiungi le seguenti variabili al tuo file `.env`:

```
EUROTAX_URL='Url Eurotax'
EUROTAX_USER='Il tuo username'
EUROTAX_PWD='La tua password'
```

## Utilizzo

```
  public function test(EurotaxService $service){
        return $service->getBrands();
    }
```