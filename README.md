# WarehouseWise
 
## `Práctica de Investigación 1: MVC`
En el apartado de Product List se puede encontrar nuestra práctica desarrollada de la investigación 1.

![1](https://user-images.githubusercontent.com/72422927/219905573-6937506a-b8e5-4528-a7c5-f6c1f318b4bd.png)

### Elementos

#### Modelos

En el proyectro se han creado 6 modelos que permiten el acceso a los datos registrados en la base de datos utilizada.
![image](https://user-images.githubusercontent.com/72422927/219905630-85583a2a-7e82-46fb-b97d-ad15060722c2.png)

#### Controladores

De igual forma los controlladores se encuentra en app>Http>Controllers
![image](https://user-images.githubusercontent.com/72422927/219905782-d09a0c15-bb38-4ad3-9a81-cb84f20ac291.png)

#### Vista

Por otra parte las vistas desarrolladas se encuentran en resources>js>Pages
![image](https://user-images.githubusercontent.com/72422927/219905688-2215014c-d1dd-4f2b-82ef-3358a113af15.png)

Al ingresar a la URL que despliega los productos, dentro del router se realiza la petición de la vista al controllador, quien a su vez consulta los modelos para brindar la información necesaria en caso de estar buscando productos filtrados.

Las válidaciones de entradas del usuario se realizan a través de reglas específicadas en los modelos creados.
