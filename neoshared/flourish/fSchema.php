<?php
/**
 * Gets schema information for the selected database
 * 
 * @copyright  Copyright (c) 2007-2010 Will Bond
 * @author     Will Bond [wb] <will@flourishlib.com>
 * @license    http://flourishlib.com/license
 * 
 * @package    Flourish
 * @link       http://flourishlib.com/fSchema
 * 
 * @version    1.0.0b39
 * @changes    1.0.0b39  Fixed a regression where key detection SQL was not compatible with PostgreSQL 8.1 [wb, 2010-04-13]
 * @changes    1.0.0b38  Added Oracle support to ::getDatabases() [wb, 2010-04-13]
 * @changes    1.0.0b37  Fixed ::getDatabases() for MSSQL [wb, 2010-04-09]
 * @changes    1.0.0b36  Fixed PostgreSQL to properly report explicit `NULL` default values via ::getColumnInfo() [wb, 2010-03-30]
 * @changes    1.0.0b35  Added `max_length` values for various text and blob data types across all databases [wb, 2010-03-29]
 * @changes    1.0.0b34  Added `min_value` and `max_value` attributes to ::getColumnInfo() to specify the valid range for numeric columns [wb, 2010-03-16]
 * @changes    1.0.0b33  Changed it so that PostgreSQL unique indexes containing functions are ignored since they can't be properly detected at this point [wb, 2010-03-14]
 * @changes    1.0.0b32  Fixed ::getTables() to not include views for MySQL [wb, 2010-03-14]
 * @changes    1.0.0b31  Fixed the creation of the default caching key for ::enableCaching() [wb, 2010-03-02]
 * @changes    1.0.0b30  Fixed the class to work with lower privilege Oracle accounts and added detection of Oracle number columns [wb, 2010-01-25]
 * @changes    1.0.0b29  Added on_delete and on_update elements to one-to-one relationship info retrieved by ::getRelationships() [wb, 2009-12-16]
 * @changes    1.0.0b28  Fixed a bug with detecting some multi-column unique constraints in SQL Server databases [wb, 2009-11-13]
 * @changes    1.0.0b27  Added a parameter to ::enableCaching() to provide a key token that will allow cached values to be shared between multiple databases with the same schema [wb, 2009-10-28]
 * @changes    1.0.0b26  Added the placeholder element to the output of ::getColumnInfo(), added support for PostgreSQL, MSSQL and Oracle "schemas", added support for parsing quoted SQLite identifiers [wb, 2009-10-22]
 * @changes    1.0.0b25  One-to-one relationships utilizing the primary key as a foreign key are now properly detected [wb, 2009-09-22]
 * @changes    1.0.0b24  Fixed MSSQL support to work with ODBC database connections [wb, 2009-09-18]
 * @changes    1.0.0b23  Fixed a bug where one-to-one relationships were being listed as many-to-one [wb, 2009-07-21]
 * @changes    1.0.0b22  PostgreSQL UNIQUE constraints that are created as indexes and not table constraints are now properly detected [wb, 2009-07-08]
 * @changes    1.0.0b21  Added support for the UUID data type in PostgreSQL [wb, 2009-06-18]
 * @changes    1.0.0b20  Add caching of merged info, improved performance of ::getColumnInfo() [wb, 2009-06-15]
 * @changes    1.0.0b19  Fixed a couple of bugs with ::setKeysOverride() [wb, 2009-06-04]
 * @changes    1.0.0b18  Added missing support for MySQL mediumint columns [wb, 2009-05-18]
 * @changes    1.0.0b17  Fixed a bug with ::clearCache() not properly reseting the tables and databases list [wb, 2009-05-13]
 * @changes    1.0.0b16  Backwards Compatibility Break - ::setCacheFile() changed to ::enableCaching() and now requires an fCache object, ::flushInfo() renamed to ::clearCache(), added Oracle support [wb, 2009-05-04]
 * @changes    1.0.0b15  Added support for the three different types of identifier quoting in SQLite [wb, 2009-03-28]
 * @changes    1.0.0b14  Added support for MySQL column definitions containing the COLLATE keyword [wb, 2009-03-28]
 * @changes    1.0.0b13  Fixed a bug with detecting PostgreSQL columns having both a CHECK constraint and a UNIQUE constraint [wb, 2009-02-27]
 * @changes    1.0.0b12  Fixed detection of multi-column primary keys in MySQL [wb, 2009-02-27]
 * @changes    1.0.0b11  Fixed an issue parsing MySQL tables with comments [wb, 2009-02-25]
 * @changes    1.0.0b10  Added the ::getDatabases() method [wb, 2009-02-24]
 * @changes    1.0.0b9   Now detects unsigned and zerofill MySQL data types that do not have a parenthetical part [wb, 2009-02-16]
 * @changes    1.0.0b8   Mapped the MySQL data type `'set'` to `'varchar'`, however valid values are not implemented yet [wb, 2009-02-01]
 * @changes    1.0.0b7   Fixed a bug with detecting MySQL timestamp columns [wb, 2009-01-28]
 * @changes    1.0.0b6   Fixed a bug with detecting MySQL columns that accept `NULL` [wb, 2009-01-19]
 * @changes    1.0.0b5   ::setColumnInfo(): fixed a bug with not grabbing the real database schema first, made general improvements [wb, 2009-01-19]
 * @changes    1.0.0b4   Added support for MySQL binary data types, numeric data type options unsigned and zerofill, and per-column character set definitions [wb, 2009-01-17]
 * @changes    1.0.0b3   Fixed detection of the data type of MySQL timestamp columns, added support for dynamic default date/time values [wb, 2009-01-11]
 * @changes    1.0.0b2   Fixed a bug with detecting multi-column unique keys in MySQL [wb, 2009-01-03]
 * @changes    1.0.0b    The initial implementation [wb, 2007-09-25]
 */
class fSchema
{
	/**
	 * The place to cache to
	 * 
	 * @var fCache
	 */
	private $cache = NULL;
	
	/**
	 * The cache prefix to use for cache entries
	 * 
	 * @var string
	 */
	private $cache_prefix;
	
	/**
	 * The cached column info
	 * 
	 * @var array
	 */
	private $column_info = array();
	
	/**
	 * The column info to override
	 * 
	 * @var array
	 */
	private $column_info_override = array();
	
	/**
	 * A reference to an instance of the fDatabase class
	 * 
	 * @var fDatabase
	 */
	private $database = NULL;
	
	/**
	 * The databases on the current database server
	 * 
	 * @var array
	 */
	private $databases = NULL;
	
	/**
	 * The cached key info
	 * 
	 * @var array
	 */
	private $keys = array();
	
	/**
	 * The key info to override
	 * 
	 * @var array
	 */
	private $keys_override = array();
	
	/**
	 * The merged column info
	 * 
	 * @var array
	 */
	private $merged_column_info = array();
	
	/**
	 * The merged key info
	 * 
	 * @var array
	 */
	private $merged_keys = array();
	
	/**
	 * The relationships in the database
	 * 
	 * @var array
	 */
	private $relationships = array();
	
	/**
	 * The tables in the database
	 * 
	 * @var array
	 */
	private $tables = NULL;
	
	
	/**
	 * Sets the database
	 * 
	 * @param  fDatabase $database  The fDatabase instance
	 * @return fSchema
	 */
	public function __construct($database)
	{
		$this->database = $database;
	}
	
	
	/**
	 * All requests that hit this method should be requests for callbacks
	 * 
	 * @internal
	 * 
	 * @param  string $method  The method to create a callback for
	 * @return callback  The callback for the method requested
	 */
	public function __get($method)
	{
		return array($this, $method);		
	}
	
	
	/**
	 * Checks to see if a column is part of a single-column `UNIQUE` key
	 * 
	 * @param  string $table   The table the column is located in
	 * @param  string $column  The column to check
	 * @return boolean  If the column is part of a single-column unique key
	 */
	private function checkForSingleColumnUniqueKey($table, $column)
	{        
		foreach ($this->merged_keys[$table]['unique'] as $key) {
			if (array($column) == $key) {
				return TRUE;
			}
		}
		if (array($column) == $this->merged_keys[$table]['primary']) {
			return TRUE;
		}
		return FALSE;
	}
	
	
	/**
	 * Clears all of the schema info out of the object and, if set, the fCache object
	 * 
	 * @internal
	 * 
	 * @return void
	 */
	public function clearCache()
	{
		$this->column_info        = array();
		$this->databases          = NULL;
		$this->keys               = array();
		$this->merged_column_info = array();
		$this->merged_keys        = array();
		$this->relationships      = array();
		$this->tables             = NULL;
		if ($this->cache) {
			$prefix = $this->makeCachePrefix();
			$this->cache->delete($prefix . 'column_info');
			$this->cache->delete($prefix . 'databases');
			$this->cache->delete($prefix . 'keys');
			$this->cache->delete($prefix . 'merged_column_info');
			$this->cache->delete($prefix . 'merged_keys');
			$this->cache->delete($prefix . 'relationships');
			$this->cache->delete($prefix . 'tables');
		}
	}
	
	
	/**
	 * Sets the schema to be cached to the fCache object specified
	 * 
	 * @param  fCache $cache      The cache to cache to
	 * @param  string $key_token  Internal use only! (this will be used in the cache key to uniquely identify the cache for this fSchema object) 
	 * @return void
	 */
	public function enableCaching($cache, $key_token=NULL)
	{
		$this->cache = $cache;
		
		if ($key_token !== NULL) {
			$this->cache_prefix = 'fSchema::' . $this->database->getType() . '::' . $key_token . '::';
		}
		$prefix = $this->makeCachePrefix();
		
		$this->column_info        = $this->cache->get($prefix . 'column_info',          array());
		$this->databases          = $this->cache->get($prefix . 'databases',            NULL);
		$this->keys               = $this->cache->get($prefix . 'keys',                 array());
		
		if (!$this->column_info_override && !$this->keys_override) {
			$this->merged_column_info = $this->cache->get($prefix . 'merged_column_info',   array());
			$this->merged_keys        = $this->cache->get($prefix . 'merged_keys',          array());  
			$this->relationships      = $this->cache->get($prefix . 'relationships',        array());
		}
		
		$this->tables             = $this->cache->get($prefix . 'tables',               NULL);   
	}
	
	
	/**
	 * Gets the column info from the database for later access
	 * 
	 * @param  string $table  The table to fetch the column info for
	 * @return void
	 */
	private function fetchColumnInfo($table)
	{
		if (isset($this->column_info[$table])) {
			return;	
		}
		
		switch ($this->database->getType()) {
			case 'db2':
				$column_info = $this->fetchDB2ColumnInfo($table);
				break;
			
			case 'mssql':
				$column_info = $this->fetchMSSQLColumnInfo($table);
				break;
			
			case 'mysql':
				$column_info = $this->fetchMySQLColumnInfo($table);
				break;
				
			case 'oracle':
				$column_info = $this->fetchOracleColumnInfo($table);
				break;
			
			case 'postgresql':
				$column_info = $this->fetchPostgreSQLColumnInfo($table);
				break;
				
			case 'sqlite':
				$column_info = $this->fetchSQLiteColumnInfo($table);
				break;
		}
			
		if (!$column_info) {
			return;	
		}
			
		$this->column_info[$table] = $column_info;
		if ($this->cache) {
			$this->cache->set($this->makeCachePrefix() . 'column_info', $this->column_info);	
		}
	}
	
	
	/**
	 * Gets the column info from a DB2 database
	 * 
	 * @param  string $table  The table to fetch the column info for
	 * @return array  The column info for the table specified - see ::getColumnInfo() for details
	 */
	private function fetchDB2ColumnInfo($table)
	{
		$column_info = array();
		
		$schema = strtolower($this->database->getUsername());
		if (strpos($table, '.') !== FALSE) {
			list ($schema, $table) = explode('.', $table);
		}
		
		$data_type_mapping = array(
			'smallint'          => 'integer',
			'integer'           => 'integer',
			'bigint'            => 'integer',
			'timestamp'         => 'timestamp',
			'date'              => 'date',
			'time'              => 'time',
			'varchar'           => 'varchar',
			'long varchar'      => 'varchar',
			'vargraphic'        => 'varchar',
			'long vargraphic'   => 'varchar',
			'character'         => 'char',
			'graphic'           => 'char',
			'real'              => 'float',
			'decimal'           => 'float',
			'numeric'           => 'float',
			'blob'              => 'blob',
			'clob'              => 'text',
			'dbclob'            => 'text'
		);
		
		$max_min_values = array(
			'smallint'   => array('min' => new fNumber(-32768),                  'max' => new fNumber(32767)),
			'integer'    => array('min' => new fNumber(-2147483648),             'max' => new fNumber(2147483647)),
			'bigint'     => array('min' => new fNumber('-9223372036854775808'),  'max' => new fNumber('9223372036854775807'))
		);
		
		// Get the column info
		$sql = "SELE