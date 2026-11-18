import os
import pymysql
import pandas as pd
from datetime import datetime

def run_import():
    excel_path = r"D:\Kuliah\Akademik\KP\futurise\file tambahan\Dataset\Perguruan Tinggi.xlsx"
    csv_path = r"D:\Kuliah\Akademik\KP\futurise\file tambahan\Dataset\statistik-smk-20261104_215115.csv"

    print("Membaca data Perguruan Tinggi...")
    df_pt = pd.read_excel(excel_path)
    records = []
    now = datetime.now().strftime("%Y-%m-%d %H:%M:%S")

    for _, row in df_pt.iterrows():
        nama = str(row["nama_pt"]).strip() if pd.notna(row["nama_pt"]) else ""
        if nama and nama.lower() != "nan":
            npsn = str(row["npsn"]).strip() if pd.notna(row["npsn"]) else None
            prov = str(row["provinsi_pt"]).strip() if pd.notna(row["provinsi_pt"]) else None
            kab = str(row["Kabupaten_pt"]).strip() if pd.notna(row["Kabupaten_pt"]) else None
            records.append((nama[:250], "perguruan_tinggi", npsn[:30] if npsn else None, prov[:100] if prov else None, kab[:100] if kab else None, now, now))

    print(f"Total Perguruan Tinggi: {len(records)}")

    print("Membaca data SMK...")
    df_smk = pd.read_csv(csv_path, sep=";", skiprows=4, low_memory=False)
    df_smk = df_smk[["Nama Sekolah", "NPSN", "Provinsi", "Kab/Kota"]].dropna(subset=["Nama Sekolah"]).drop_duplicates(subset=["Nama Sekolah", "NPSN"])

    for _, row in df_smk.iterrows():
        nama = str(row["Nama Sekolah"]).strip() if pd.notna(row["Nama Sekolah"]) else ""
        if nama and nama.lower() != "nan":
            npsn = str(row["NPSN"]).strip() if pd.notna(row["NPSN"]) else None
            prov = str(row["Provinsi"]).strip() if pd.notna(row["Provinsi"]) else None
            kab = str(row["Kab/Kota"]).strip() if pd.notna(row["Kab/Kota"]) else None
            records.append((nama[:250], "smk", npsn[:30] if npsn else None, prov[:100] if prov else None, kab[:100] if kab else None, now, now))

    print(f"Total seluruh institusi: {len(records)}")

    print("Menghubungkan ke MySQL database 'futurise'...")
    conn = pymysql.connect(
        host="127.0.0.1",
        port=3306,
        user="root",
        password="",
        database="futurise",
        charset="utf8mb4"
    )

    with conn.cursor() as cursor:
        print("Membersihkan data referensi lama jika ada...")
        cursor.execute("TRUNCATE TABLE referensi_institusi")
        
        insert_sql = """
            INSERT INTO referensi_institusi (nama, kategori, npsn, provinsi, kab_kota, created_at, updated_at)
            VALUES (%s, %s, %s, %s, %s, %s, %s)
        """
        
        batch_size = 2000
        for i in range(0, len(records), batch_size):
            chunk = records[i:i + batch_size]
            cursor.executemany(insert_sql, chunk)
            print(f"Tersimpan {min(i + batch_size, len(records))}/{len(records)} baris...")

    conn.commit()
    conn.close()
    print("[SUKSES] Berhasil mengimpor seluruh data institusi ke database MySQL!")

if __name__ == "__main__":
    run_import()
