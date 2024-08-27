import mysql.connector
from mysql.connector import Error

def export_tables_to_txt(db_config, output_file):
    connection = None
    try:
        # Koneksi ke database
        connection = mysql.connector.connect(**db_config)
        cursor = connection.cursor()
        
        # Mengambil daftar tabel
        cursor.execute("SHOW TABLES")
        tables = cursor.fetchall()
        
        # Menulis struktur tabel ke file
        with open(output_file, 'w') as file:
            for table in tables:
                table_name = table[0]
                file.write(f"Table: {table_name}\n")
                
                # Mengambil deskripsi kolom untuk tabel
                cursor.execute(f"DESCRIBE {table_name}")
                columns = cursor.fetchall()
                file.write("Column Name | Data Type | Null | Key | Default | Extra\n")
                file.write("-" * 60 + "\n")
                
                for column in columns:
                    file.write(f"{column[0]} | {column[1]} | {column[2]} | {column[3]} | {column[4]} | {column[5]}\n")
                file.write("\n")
        
        print(f"Export completed! Data saved to {output_file}.")
    
    except Error as e:
        print(f"Error: {e}")
    
    finally:
        if connection and connection.is_connected():
            cursor.close()
            connection.close()

# Konfigurasi database
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',  # Kosongkan jika tidak ada password
    'database': 'pemancingan'
}

# Ekspor struktur tabel ke file teks
export_tables_to_txt(db_config, 'tables_structure.txt')
