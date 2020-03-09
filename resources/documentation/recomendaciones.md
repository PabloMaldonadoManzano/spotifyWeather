### Get Recomendaciones 
                
**URL**
 
    `/api/sugerencia`
 
  **Method:**
 
   `GET`
 
  **Data Params**
  
      'city' => 'required if lat and lot is null',
      'lat' => 'required if city is null',
      'lon' => 'required if city is null',
      
  **Headers:**

     'X-Requested-With' => 'XMLHttpRequest',
 
 **Success Response:**
 
  * **Code:** 200 <br />
   **Content:** 
 
         {
           "temperature": 21.16,
           "tracks": [
             " Track: Don't Start Now Artist: Dua Lipa",
             " Track: Intentions Artist: Justin Bieber",
             " Track: Blinding Lights Artist: The Weeknd",
             " Track: Say So Artist: Doja Cat",
             " Track: The Box Artist: Roddy Ricch",
             " Track: La Difícil Artist: Bad Bunny",
             " Track: Stupid Love Artist: Lady Gaga",
             " Track: Falling Artist: Trevor Daniel",
             " Track: Blueberry Faygo Artist: Lil Mosey",
             " Track: Sunday Best Artist: Surfaces"
           ]
         }
             
             
 **Error Response:**
 
   * **Code:** 422 Unprocessable Entity <br />
     **Content:** 
     
            {
              "message": "The given data was invalid.",
              "errors": {
                "city": [
                  "El parametro city es requerido."
                ],
                "lon": [
                  "El parameto lon es requerido."
                ],
                "lat": [
                  "El parameto lat es requerido."
                ]
              }
            }
          
   * **Code:** 500 Internal server error <br />
        **Content:** 
        
               {
                 "message": "Ha ocurrido un error : Ocurrio un error al obtener el clima."
               }
               
   * **Code:** 500 Internal server error <br />
           **Content:** 
           
                  {
                    "message": "Ha ocurrido un error : Ocurrio un error al obtener el token de spotify."
                  }
                  
   * **Code:** 500 Internal server error <br />
           **Content:** 
           
                  {
                    "message": "Ha ocurrido un error : Ocurrio un error al obtener el playlist."
                  }
                  
   * **Code:** 500 Internal server error <br />
           **Content:** 
           
                  {
                    "message": "Ha ocurrido un error : Ocurrio un error al obtener los tracks."
                  }
                  
