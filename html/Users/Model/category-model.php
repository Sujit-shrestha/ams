<?php

namespace Model;

use Configg\DBConnect;
use Exception;

class Category
{
  public $DBconn;

  public function __construct(DBConnect $DBconn)
  {
    $this->DBconn = $DBconn;
  }

  public function getAll()
  {
    try {
      $sql = "
        SELECT * FROM category
      ";
      $result = $this->DBconn->conn->query($sql);

      if (!$result->num_rows > 0) {
        throw new Exception("Cannot get form database!!");
      }
      $data = array();
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
      }
      return [
        "status" => "true",
        "message" => "Data extracted successfully",
        "data" => $data
      ];

    } catch (Exception $e) {
      return [
        "status" => "false",
        "message" => $e->getMessage(),
        "data" => []
      ];
    }
  }
  public function getByName($name){
    try{
      $sql = "
   SELECT * from category WHERE category_name = '$name'
   ";
   $result = $this->DBconn->conn->query($sql);
   $row = $result->fetch_assoc();
  
  if($result -> num_rows < 1){
   throw new Exception("Data not found.");
  }
  
   return [
     "status" => true ,
     "message" => "Row extracted.",
     "data" => $row
   ];
   }catch(Exception $e){
     return [
       "status" => false ,
       "message" => $e ->getMessage(),
       "data" => []
     ];
   }
  }
public function getById($id){
  try{
     $sql = "
  SELECT * from category WHERE id = '$id'
  ";
  $result = $this->DBconn->conn->query($sql);
  $row = $result->fetch_assoc();
 
 if($result -> num_rows < 1){
  throw new Exception("Data not found.");
 }
 
  return [
    "status" => true ,
    "message" => "Row extracted.",
    "data" => $row
  ];
  }catch(Exception $e){
    return [
      "status" => false ,
      "message" => $e ->getMessage(),
      "data" => []
    ];
  }
 
}
  public function getChild( $parentId ) {
    $sql = "SELECT * FROM category WHERE parent='$parentId'";
    $result = $this->DBconn->conn->query($sql);
    $data = array();
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }
    return [
      "status" => "true",
      "message" => "Data extracted successfully!!",
      "data" => $data
    ];
  }
public function getParent (){
  try{
    $sql = "SELECT * FROM category WHERE parent IS NULL"; 
    $result = $this->DBconn->conn->query($sql);
      $data = array();
        while ($row = $result->fetch_assoc()) {
          $data[] = $row;
        }
        

        return [
          "status" => "true",
          "message" => "Data extracted successfully!!",
          "data" => $data
        ];
  }catch(Exception $e){
    return [
      "status" => "false",
      "message" => $e->getMessage(),
      "data" => []
    ];
  }
}
  public function get(string $category_name = NULL , string $parent = NULL)
  {
    try {
      
      $sql = "SELECT * FROM category WHERE parent IS NULL";  
      
    
      $result = $this->DBconn->conn->query($sql);
      $data = array();
        while ($row = $result->fetch_assoc()) {
          $data[] = $row;
        }
        

        return [
          "status" => "true",
          "message" => "Data extracted successfully!!",
          "data" => $data
        ];
   
    
      // $result = $this->DBconn->conn->query($sql);

      if (!$result->num_rows > 0) {
        throw new Exception("Unable to fetch the parameter provided !!");
      } else {
        $data = array();
        while ($row = $result->fetch_assoc()) {
          $data[] = $row;
        }
        return [
          "status" => "true",
          "message" => "Data extracted successfully!!",
          "data" => $data
        ];
      }
    } catch (Exception $e) {
      return [
        "status" => "false",
        "message" => $e->getMessage(),
        "data" => []
      ];
    }
  }
  public function update($data)
  {
    try {
      //set new Value into newValue from parent or child
      $newValue = NULL;
      if(!isset($data["newParent"] )){
        $newValue = $data["newChild"];
      }else{
        $newValue = $data["newParent"];
      }

      $sql = "UPDATE category ";

        $sql .= "
         SET category_name = '$newValue'
      WHERE id = $data[id]
        ";
      
      
      $result = $this->DBconn->conn->query($sql);

      if (!$result) {
        throw new Exception("Unable to update in database!!");
      }
      return [
        "status" => "true",
        "message" => "Value updated successfully",
        "data" => [
          $data
        ]
      ];

    } catch (Exception $e) {
      return [
        "status" => "false",
        "message" => $e->getMessage()
      ];
    }
  }

  public function updateParent(string $previousParent, string $newParent)
  {
    try {
      $sql = "UPDATE category 
      SET parent = '$newParent'
      WHERE parent = '$previousParent'
      ";
      $result = $this->DBconn->conn->query($sql);

      if (!$result) {
        throw new Exception("Unable to update in database!!");
      }
      return [
        "status" => "true",
        "message" => "Parent updated successfully",
      ];

    } catch (Exception $e) {
      return [
        "status" => "false",
        "message" => $e->getMessage()
      ];
    }
  }
  public function updateCategory(string $previousChild, string $newChild)
  {
    try {
      $sql = "UPDATE category
         SET category_name = '$newChild'
        WHERE category_name = '$previousChild'
      ";
      $result = $this->DBconn->conn->query($sql);
      if (!$result) {
        throw new Exception("Unable to update category in database!!");
      }
      return [
        "status" => "true",
        "message" => "Category updated successfully."
      ];
    } catch (Exception $e) {
      return [
        "status" => "false",
        "message" => $e->getMessage(),
      ];
    }
  }

  public function deleteParent(string $parentCategory)
  {
    try {
      $sql = "
      DELETE FROM category
      WHERE parent = '$parentCategory'
      ";
      $result = $this->DBconn->conn->query($sql);
      if (!$result) {
        throw new Exception("Unable to delete parent in database!!");
      }
      return [
        "status" => "true",
        "message" => "Parent Category deleted successfully.",
      ];

    } catch (Exception $e) {
      return [
        "status" => "false",
        "message" => $e->getMessage(),
      ];

    }
  }
  public function deleteChildBasedOnParentId($parentId){
    try {
      $sql = "
      DELETE FROM category
      WHERE parent = '$parentId'
      ";
      $result = $this->DBconn->conn->query($sql);
      if (!$result) {
        throw new Exception("Unable to delete given id from database!!");
      }
      return [
        "status" => true,
        "message" => " Data deleted successfully.",
      ];

    } catch (Exception $e) {
      return [
        "status" => "false",
        "message" => $e->getMessage(),
      ];
    }
  }
  public function delete($id)
  {
    try {
      $sql = "
      DELETE FROM category
      WHERE id = '$id'
      ";
      $result = $this->DBconn->conn->query($sql);
      if (!$result) {
        throw new Exception("Unable to delete given id from database!!");
      }
      return [
        "status" => true,
        "message" => " Data deleted successfully.",
      ];

    } catch (Exception $e) {
      return [
        "status" => "false",
        "message" => $e->getMessage(),
      ];
    }
  }
  public function getIdbyNameandParent($category_name , $parentId){
    try{

      $sql = "
      SELECT id FROM category
      WHERE category_name = '$category_name' AND parent = '$parentId' 
      ";
      $result = $this->DBconn->conn->query($sql);
  
         if (!$result) {
           throw new Exception("Could not find ");
         }
         $result = $result->fetch_assoc();
        
         return [
          "status" => true ,
          "message" => "Id fetched",
          "data" => $result["id"]
         ];
        }catch(Exception $e){
          return [
            "status" => false ,
            "message" => $e->getMessage(),
            "data" => null
          ];
        }
    

  }
  public function getIdByName($categoryName){
    try{
      $sql = "SELECT id FROM category 
      WHERE category_name = '$categoryName' ";
       $result = $this->DBconn->conn->query($sql);

       if (!$result) {
         throw new Exception("Could not find ");
       }
       $result = $result->fetch_assoc();
       
       return [
        "status" => true ,
        "message" => "Id fetched for " ."$categoryName",
        "data" => $result["id"]
       ];
    }catch(Exception $e){
      return [
        "status" => false ,
        "message" => $e->getMessage(),
        "data" => null
      ];
    }
  }
public function createParent($data){

  try{
    $data = json_decode($data, true);

  
    //sql for parent creation where parent column remains null
    $sql = "
    INSERT INTO category
    (category_name , parent)
    VALUES 
    ('$data[parent]' , NULL)
    ";
    $result = $this->DBconn->conn->query($sql);

      if (!$result) {
        throw new Exception("Could not insert into database!!");
      }
      $id = $this->getIdByName($data['parent']);
     
    return [
      "status" => false,
      "message" => "Created successfully",
      "data" => [
        "id" =>      $id['data'] ,
        "name" => $data['parent']
      ]
    ];
  }catch(Exception $e){
    return [
      "status" => false,
      "message" => $e->getMessage()
    ];
  }
}
  public function create($data)
  {
    try {
      $data = json_decode($data, true);
      $sql = "
    INSERT INTO category
    (category_name , parent)
    VALUES 
    ('$data[category_name]' , '$data[parent]')
    ";
      $result = $this->DBconn->conn->query($sql);

      if (!$result) {
        throw new Exception("Could not insert into database!!");
      }
      $sqlToGetId = "
      SELECT * FROM category 
      WHERE category_name = '{$data['category_name']}' AND parent = '{$data['parent']}'
  ";

      // to get the id of created row
      $result = $this->DBconn->conn->query($sqlToGetId);
      $row = $result->fetch_assoc();


      return [
        "status" => "true",
        "message" => "Category created successfully.",
        "data" => $row
          
        
      ];
    } catch (Exception $e) {
      return [
        "status" => "false",
        "message" => $e->getMessage()
      ];
    }
  }
}
