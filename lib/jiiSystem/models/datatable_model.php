<?php
Class Datatable {

	protected $server;

	protected $columns = array();

	protected $table;

	protected $sLimit;

	protected $sOrder;

	protected $sWhere;


	public function __construct($server)
	{
		$this->server = $server;
	}


	public function get()
	{

		$sCols = array();
		foreach ($this->columns as $key => $value)
		{
			$sCols[count($sCols)] = $key.' as '.$value;
		}

		$sql = "SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $sCols))."
		$this->sFrom
		$this->sWhere
		$this->sOrder
		$this->sLimit";

		$sth = $this->server->connection->getStatement($sql);
		$sth->execute();
		return  $sth->fetchAll();
	}

	/* 
	 * Select
	 */
	public function select($aColumns = array())
	{
		$this->columns = $aColumns;

		return $this;
	}

	/* 
	 * FROM
	 */
	public function from($table)
	{
		$this->table = $table;
		$this->sFrom = "FROM {$this->server->loginDatabase}.$this->table ";

		return $this;
	}

	public function join($aTable = array())
	{
		foreach($aTable as $table)
		{
			$this->sFrom .= "JOIN {$this->server->loginDatabase}.$table ";
		}

		return $this;
	}


	/* 
	 * Paging
	 */
	public function limit($iDisplayStart = 0, $iDisplayLength = 0)
	{
		$this->sLimit = '';
		if (isset($iDisplayStart) && $iDisplayLength != '-1')
		{
			$this->sLimit = 'LIMIT ' .intval($iDisplayStart). ', '.
				intval($iDisplayLength);
		}

		return $this;
	}

	/* 
	 * Ordering
	 */
	public function order($iSortCol_0, $iSortingCols = 0)
	{
		$sCol = array();
		$aCol = array();
		foreach ($this->columns as $key => $value)
		{
			$sCol[count($sCol)] = $key;
			$aCol[count($aCol)] = $value;
		}

		$this->sOrder = '';
		if (isset($iSortCol_0))
		{
			$this->sOrder = 'ORDER BY  ';
			for ( $i=0 ; $i < intval( $iSortingCols ) ; $i++ )
			{
				if ($_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true")
				{
					$this->sOrder .= ''.$sCol[ intval( $_GET['iSortCol_'.$i] ) ].' '.
						($_GET['sSortDir_'.$i] === 'asc' ? 'asc' : 'desc') .', ';
						
				}
			}
			
			$this->sOrder = substr_replace( $this->sOrder, '', -2 );
			if ( $this->sOrder == 'ORDER BY' )
			{
				$this->sOrder = '';
			}
		}

		return $this;
	}

	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	public function where($sSearch, $aUnsearchableColumns = array())
	{
		//Convert
		$sCol = array();
		$aCol = array();
		foreach ($this->columns as $key => $value)
		{
			$sCol[count($sCol)] = $key;
			$aCol[count($aCol)] = $value;
		}

		$this->sWhere = '';
		if (isset($sSearch) && $sSearch != '')
		{
			$i = 0;
			$this->sWhere = 'WHERE (';
			for ($i=0 ; $i < count($this->columns) ; $i++)
			{
				// if (isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true")
				// {
					$colInclude = true;
					foreach ($aUnsearchableColumns as $usVal)
					{
						if($aCol[$i] == $usVal)
						{
							$colInclude = false;
						}
					}
					if($colInclude == true)
					{
						//
						if($sSearch == 'post:Published' OR $sSearch == 'post:published')
						{
							$this->sWhere .= "".$sCol[$i]." = '1' OR ";
						}
						elseif($_GET['sSearch'] == 'post:Draft' OR $sSearch == 'post:draft')
						{
							$this->sWhere .= "".$sCol[$i]." = '0' OR ";
						}

						$this->sWhere .= "".$sCol[$i]." LIKE '%" .$sSearch. "%' OR ";
					}
						
					// }
				//}
			}
			$this->sWhere = substr_replace($this->sWhere, '', -3);
			$this->sWhere .= ')';
		}

		/* Individual column filtering */
		for ($i=0 ; $i < count($this->columns) ; $i++)
		{
			if (isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '')
			{
				if ($this->sWhere == "")
				{
					$this->sWhere = "WHERE ";
				}
				else
				{
					$this->sWhere .= " AND ";
				}
				$this->sWhere .= "".$sCol[$i]." LIKE '%".$_GET['sSearch_'.$i]."%' ";
			}
		}

		return $this;
	}


	public function filterTotal()
	{
		/* Data set length after filtering */
		$sql = " SELECT FOUND_ROWS() as foundRow ";
		$sth = $this->server->connection->getStatement($sql);
		$sth->execute();
		return $sth->fetch()->foundRow;
	}

	public function totalRow($iId = 0)
	{
		/* Total data set length */
		$sql = "SELECT count(`$iId`) as total FROM {$this->server->loginDatabase}.$this->table";
		$sth = $this->server->connection->getStatement($sql);
		$sth->execute();
		return $sth->fetch()->total;
	}
}