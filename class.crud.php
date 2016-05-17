<?php
/*
the main class file which contains code for database operations.
create() and update() functions are in try/catch block to handle exceptions.

dataview() function selects the whole records from database table.

paging() function set’s the QueryString like “page_no=number”.

paginglink() function creates the paging number links with “previoue and next” feature.

all the CRUD and Pagination operations are done by this file.


*/
class crud
{
	private $db;
	//建構者：連線資料庫
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}
	/*
	查詢操作主要是PDO::query()、PDO::exec()、PDO::prepare()。
	PDO::query()---->主要是用於有記錄 結果返回的操作，特別是SELECT操作，
	PDO::exec()-->主要是針對沒有結果集合返回的操作，比如INSERT、UPDATE、DELETE等操作，
				它返回的結果是當前操作影響的列數。
	*/

	public function create($fname, $lname, $email, $contact)
	{
		try
		{
			/*
			PDO::prepare()主要是預處理操作，需要通過$rs-＞execute()來執行預處理裡面的SQL 語句，
			這個方法可以綁定參數
			*/
			$stmt = $this->db->prepare("INSERT INTO tbl_users(first_name,last_name,email_id,contact_no) VALUES(:fname, :lname, :email, :contact)");
  			$stmt->bindparam(":fname",$fname);
   			$stmt->bindparam(":lname",$lname);
   			$stmt->bindparam(":email",$email);
   			$stmt->bindparam(":contact",$contact);
   			$stmt->execute();
   			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			return false;

		}
	}

    /*拿到欲處理的ID
    */
	public function getID($id)
	{
		$stmt = $this->db->prepare("SELECT * FROM tbl_users WHERE id=:id");
  		$stmt->execute(array(":id"=>$id));
 		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
 		return $editRow;
 	}

	public function update($id,$fname,$lname,$email,$contact)
	{
		try
		{
			$stmt=$this->db->prepare("UPDATE tbl_users SET first_name=:fname, last_name=:lname, email_id=:email, contact_no=:contact WHERE id=:id ");
   			$stmt->bindparam(":fname",$fname);
   			$stmt->bindparam(":lname",$lname);
   			$stmt->bindparam(":email",$email);
   			$stmt->bindparam(":contact",$contact);
   			$stmt->bindparam(":id",$id);
   			$stmt->execute();
   			return true; 
  		}
  		catch(PDOException $e)
  		{
  			echo $e->getMessage();
  			return false;
  		}
	}

	public function delete($id)
	{
		$stmt = $this->db->prepare("DELETE FROM tbl_users WHERE id = :id");
		$stmt->bindparam(":id",$id);
  		$stmt->execute();
 		return true;
	}
	/* paging */	
 	public function dataview($query)
 	{
  		$stmt = $this->db->prepare($query);
  		$stmt->execute();
 		if($stmt->rowCount()>0)
 		{
   			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
   			{
    		?>
    <!-- html
	-->
                <tr>
                <td><?php print($row['id']); ?></td>
                <td><?php print($row['first_name']); ?></td>
                <td><?php print($row['last_name']); ?></td>
                <td><?php print($row['email_id']); ?></td>
                <td><?php print($row['contact_no']); ?></td>
                <td align="center">
                <a href="edit-data.php?edit_id=<?php print($row['id']); ?>"><i class="glyphicon glyphicon-edit"></i></a>
                </td>
                <td align="center">
                <a href="delete.php?delete_id=<?php print($row['id']); ?>"><i class="glyphicon glyphicon-remove-circle"></i></a>
                </td>
                </tr>
                <?php
            }
  		}
  		else
		{
   		?>
            <tr>
            <td>Nothing here...</td>
            </tr>
            <?php
  		}
  
 		}
 //end function dataview
 //end paging

	/*
	function paging return updated query2
	*/
	public function paging($query,$records_per_page)
	{
  		$starting_position=0;
  		/*
  		The isset () function is used to check whether a variable is set or not. 
  		If a variable is already unset with unset() function, i
  		t will no longer be set. 
  		The isset() function return false if testing variable contains a NULL value. 
  		*/
  		if(isset($_GET["page_no"]))
  		{
   			$starting_position=($_GET["page_no"]-1)*$records_per_page;//計算starting_position

  		}
  		$query2=$query." limit $starting_position,$records_per_page";//把query2加上
  		return $query2;
  	}

  	/*
  	*/
  	public function paginglink($query,$records_per_page)
	{
		
		$self = $_SERVER['PHP_SELF'];
		
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		
		$total_no_of_records = $stmt->rowCount();
		
		if($total_no_of_records > 0)
		{
			?>
			<!--html
				unorder list
		-->
			<ul class="pagination">
			<?php
			$total_no_of_pages=ceil($total_no_of_records/$records_per_page);
			$current_page=1;
			if(isset($_GET["page_no"]))
			{
				$current_page=$_GET["page_no"];
			}
			if($current_page!=1)
			{
				$previous =$current_page-1;
				echo "<li><a href='".$self."?page_no=1'>First</a></li>";
				echo "<li><a href='".$self."?page_no=".$previous."'>Previous</a></li>";
			}
			for($i=1;$i<=$total_no_of_pages;$i++)
			{
				if($i==$current_page)
				{
					echo "<li><a href='".$self."?page_no=".$i."' style='color:red;'>".$i."</a></li>";
				}
				else
				{
					echo "<li><a href='".$self."?page_no=".$i."'>".$i."</a></li>";
				}
			}
			if($current_page!=$total_no_of_pages)
			{
				$next=$current_page+1;
				echo "<li><a href='".$self."?page_no=".$next."'>Next</a></li>";
				echo "<li><a href='".$self."?page_no=".$total_no_of_pages."'>Last</a></li>";
			}
			?>
			<!-- end of unorder list
			     end of html
		-->
			</ul>
			<?php
		}
	}
	/*end of public function paginglink
	*/
}
?>
