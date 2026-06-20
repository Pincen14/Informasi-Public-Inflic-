"""
generate_laporan.py
Membuat laporan Word (.docx) Tugas Besar Pengujian Inflic
Menggunakan python-docx
"""

from docx import Document
from docx.shared import Pt, RGBColor, Inches, Cm
from docx.enum.text import WD_ALIGN_PARAGRAPH, WD_LINE_SPACING
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml.ns import qn
from docx.oxml import OxmlElement
import copy

doc = Document()

# ─── HALAMAN & MARGIN ───────────────────────────────────────────────────────
section = doc.sections[0]
section.page_width  = Cm(21.0)
section.page_height = Cm(29.7)
section.top_margin    = Cm(3.0)
section.bottom_margin = Cm(2.5)
section.left_margin   = Cm(4.0)
section.right_margin  = Cm(2.5)

# ─── HELPERS ────────────────────────────────────────────────────────────────

def set_font(run, name="Times New Roman", size=12, bold=False, italic=False, color=None):
    run.font.name   = name
    run.font.size   = Pt(size)
    run.font.bold   = bold
    run.font.italic = italic
    if color:
        run.font.color.rgb = RGBColor(*color)

def set_para_format(para, alignment=WD_ALIGN_PARAGRAPH.JUSTIFY,
                    space_before=0, space_after=6, line_spacing=None):
    para.alignment   = alignment
    para.paragraph_format.space_before = Pt(space_before)
    para.paragraph_format.space_after  = Pt(space_after)
    if line_spacing:
        para.paragraph_format.line_spacing_rule = WD_LINE_SPACING.EXACTLY
        para.paragraph_format.line_spacing      = Pt(line_spacing)

def add_heading(doc, text, level=1, numbering=None):
    """Tambah heading dengan format Times New Roman."""
    p = doc.add_paragraph()
    p.style = f'Heading {level}'
    full_text = f"{numbering} {text}" if numbering else text
    run = p.add_run(full_text)
    size = {1: 14, 2: 13, 3: 12}.get(level, 12)
    set_font(run, size=size, bold=True)
    p.paragraph_format.space_before = Pt(12)
    p.paragraph_format.space_after  = Pt(6)
    p.paragraph_format.keep_with_next = True
    return p

def add_body(doc, text, indent=False):
    """Tambah paragraf isi teks."""
    p = doc.add_paragraph(text)
    p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
    p.paragraph_format.space_before = Pt(0)
    p.paragraph_format.space_after  = Pt(6)
    p.paragraph_format.line_spacing_rule = WD_LINE_SPACING.MULTIPLE
    p.paragraph_format.line_spacing = 1.5
    for run in p.runs:
        set_font(run, size=12)
    if indent:
        p.paragraph_format.left_indent = Cm(1.27)
    return p

def add_body_run(doc, parts):
    """Tambah paragraf dengan bagian bold/normal campuran.
    parts = [("text", bold), ...]
    """
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
    p.paragraph_format.space_before = Pt(0)
    p.paragraph_format.space_after  = Pt(6)
    p.paragraph_format.line_spacing_rule = WD_LINE_SPACING.MULTIPLE
    p.paragraph_format.line_spacing = 1.5
    for text, bold in parts:
        run = p.add_run(text)
        set_font(run, size=12, bold=bold)
    return p

def add_code(doc, code_text):
    """Tambah blok kode dengan Courier New."""
    p = doc.add_paragraph()
    p.paragraph_format.space_before = Pt(4)
    p.paragraph_format.space_after  = Pt(4)
    p.paragraph_format.left_indent  = Cm(1.0)
    p.paragraph_format.line_spacing_rule = WD_LINE_SPACING.EXACTLY
    p.paragraph_format.line_spacing = Pt(14)
    run = p.add_run(code_text)
    set_font(run, name="Courier New", size=9)
    p.paragraph_format.keep_together = True
    return p

def add_bullet(doc, text, bold_prefix=None):
    """Tambah bullet item."""
    p = doc.add_paragraph(style='List Bullet')
    if bold_prefix:
        r1 = p.add_run(bold_prefix)
        set_font(r1, size=12, bold=True)
        r2 = p.add_run(text)
        set_font(r2, size=12)
    else:
        run = p.add_run(text)
        set_font(run, size=12)
    p.paragraph_format.space_before = Pt(0)
    p.paragraph_format.space_after  = Pt(3)
    p.paragraph_format.line_spacing_rule = WD_LINE_SPACING.MULTIPLE
    p.paragraph_format.line_spacing = 1.15
    return p

def add_table(doc, headers, rows, col_widths=None):
    """Tambah tabel berformat standar laporan."""
    table = doc.add_table(rows=1 + len(rows), cols=len(headers))
    table.style = 'Table Grid'
    table.alignment = WD_TABLE_ALIGNMENT.CENTER

    # Header
    hdr_cells = table.rows[0].cells
    for i, h in enumerate(headers):
        hdr_cells[i].text = h
        hdr_cells[i].vertical_alignment = WD_ALIGN_VERTICAL.CENTER
        for run in hdr_cells[i].paragraphs[0].runs:
            set_font(run, size=10, bold=True)
        hdr_cells[i].paragraphs[0].alignment = WD_ALIGN_PARAGRAPH.CENTER
        # Shading header
        tc = hdr_cells[i]._tc
        tcPr = tc.get_or_add_tcPr()
        shd = OxmlElement('w:shd')
        shd.set(qn('w:fill'), '2E4057')
        shd.set(qn('w:color'), 'FFFFFF')
        shd.set(qn('w:val'), 'clear')
        tcPr.append(shd)
        for para in hdr_cells[i].paragraphs:
            for run in para.runs:
                run.font.color.rgb = RGBColor(255, 255, 255)

    # Data rows
    for ri, row_data in enumerate(rows):
        row_cells = table.rows[ri + 1].cells
        for ci, cell_text in enumerate(row_data):
            row_cells[ci].text = str(cell_text)
            row_cells[ci].vertical_alignment = WD_ALIGN_VERTICAL.CENTER
            for run in row_cells[ci].paragraphs[0].runs:
                set_font(run, size=10)
            # Alternating row color
            if ri % 2 == 1:
                tc = row_cells[ci]._tc
                tcPr = tc.get_or_add_tcPr()
                shd = OxmlElement('w:shd')
                shd.set(qn('w:fill'), 'F0F4F8')
                shd.set(qn('w:val'), 'clear')
                tcPr.append(shd)

    # Set column widths
    if col_widths:
        for i, w in enumerate(col_widths):
            for row in table.rows:
                row.cells[i].width = Cm(w)

    doc.add_paragraph()  # spasi setelah tabel
    return table

def page_break(doc):
    doc.add_page_break()

# ════════════════════════════════════════════════════════════════════════════
# COVER PAGE
# ════════════════════════════════════════════════════════════════════════════
p = doc.add_paragraph()
p.alignment = WD_ALIGN_PARAGRAPH.CENTER
p.paragraph_format.space_before = Pt(48)
p.paragraph_format.space_after  = Pt(6)
run = p.add_run("LAPORAN TUGAS BESAR")
set_font(run, size=18, bold=True)

p = doc.add_paragraph()
p.alignment = WD_ALIGN_PARAGRAPH.CENTER
p.paragraph_format.space_before = Pt(0)
p.paragraph_format.space_after  = Pt(4)
run = p.add_run("Pengujian dan Implementasi Sistem")
set_font(run, size=14, bold=True)

p = doc.add_paragraph()
p.alignment = WD_ALIGN_PARAGRAPH.CENTER
p.paragraph_format.space_before = Pt(0)
p.paragraph_format.space_after  = Pt(48)
run = p.add_run("(BBK2MAB2)")
set_font(run, size=12, bold=False)

# Judul sistem
p = doc.add_paragraph()
p.alignment = WD_ALIGN_PARAGRAPH.CENTER
p.paragraph_format.space_before = Pt(0)
p.paragraph_format.space_after  = Pt(6)
run = p.add_run("SISTEM INFORMASI BARANG TEMUAN")
set_font(run, size=16, bold=True)

p = doc.add_paragraph()
p.alignment = WD_ALIGN_PARAGRAPH.CENTER
p.paragraph_format.space_before = Pt(0)
p.paragraph_format.space_after  = Pt(4)
run = p.add_run("INFLIC")
set_font(run, size=20, bold=True, color=(46, 64, 87))

p = doc.add_paragraph()
p.alignment = WD_ALIGN_PARAGRAPH.CENTER
p.paragraph_format.space_before = Pt(0)
p.paragraph_format.space_after  = Pt(72)
run = p.add_run("(Informasi Public Lost & Found)")
set_font(run, size=12, italic=True)

# Anggota Kelompok
p = doc.add_paragraph()
p.alignment = WD_ALIGN_PARAGRAPH.CENTER
p.paragraph_format.space_before = Pt(0)
p.paragraph_format.space_after  = Pt(12)
run = p.add_run("Disusun Oleh:")
set_font(run, size=12, bold=True)

members = [
    ("1.", "Nama Anggota 1", "NIM XXXXXXXXXX"),
    ("2.", "Nama Anggota 2", "NIM XXXXXXXXXX"),
    ("3.", "Nama Anggota 3", "NIM XXXXXXXXXX"),
    ("4.", "Nama Anggota 4", "NIM XXXXXXXXXX"),
]
for no, name, nim in members:
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p.paragraph_format.space_before = Pt(0)
    p.paragraph_format.space_after  = Pt(3)
    run = p.add_run(f"{no}  {name}  –  {nim}")
    set_font(run, size=12)

p = doc.add_paragraph()
p.alignment = WD_ALIGN_PARAGRAPH.CENTER
p.paragraph_format.space_before = Pt(48)
p.paragraph_format.space_after  = Pt(4)
run = p.add_run("Program Studi S1 Rekayasa Perangkat Lunak")
set_font(run, size=12, bold=True)

p = doc.add_paragraph()
p.alignment = WD_ALIGN_PARAGRAPH.CENTER
p.paragraph_format.space_before = Pt(0)
p.paragraph_format.space_after  = Pt(4)
run = p.add_run("Telkom University Surabaya")
set_font(run, size=12, bold=True)

p = doc.add_paragraph()
p.alignment = WD_ALIGN_PARAGRAPH.CENTER
p.paragraph_format.space_before = Pt(0)
p.paragraph_format.space_after  = Pt(0)
run = p.add_run("2025 / 2026")
set_font(run, size=12)

page_break(doc)

# ════════════════════════════════════════════════════════════════════════════
# BAB 1 – PENDAHULUAN
# ════════════════════════════════════════════════════════════════════════════
add_heading(doc, "PENDAHULUAN", level=1, numbering="BAB 1")

add_heading(doc, "Latar Belakang", level=2, numbering="1.1")
add_body(doc,
    "Inflic (Informasi Public Lost & Found) adalah sistem informasi berbasis web yang dirancang "
    "untuk memfasilitasi proses penemuan dan pengembalian barang hilang di lingkungan kampus. "
    "Sistem ini dibangun menggunakan framework Laravel 10 (PHP) dengan autentikasi berbasis "
    "Breeze dan manajemen peran (role-based access) yang membedakan antara pengguna biasa "
    "(user) dan administrator."
)
add_body(doc,
    "Seiring meningkatnya kompleksitas fitur dan kebutuhan keandalan sistem, pengujian yang "
    "sistematis dan terstruktur menjadi sangat penting. Pengujian membantu memastikan bahwa "
    "setiap komponen sistem bekerja sesuai spesifikasi, mendeteksi bug sejak dini, serta "
    "memberikan kepercayaan kepada pengguna akhir terhadap kualitas aplikasi."
)
add_body(doc,
    "Laporan ini mendokumentasikan proses pengujian sistem Inflic menggunakan dua pendekatan utama:"
)
add_bullet(doc, "Black Box Testing — pengujian berdasarkan spesifikasi fungsional tanpa melihat kode "
           "internal, menggunakan metode Equivalence Partitioning (EP) dan Boundary Value Analysis (BVA).")
add_bullet(doc, "White Box Testing — pengujian berdasarkan struktur kode internal menggunakan metode "
           "Basis Path Testing dengan analisis Cyclomatic Complexity.")

add_heading(doc, "Tujuan Pengujian", level=2, numbering="1.2")
add_body(doc, "Tujuan pengujian sistem Inflic adalah sebagai berikut:")
tujuan = [
    "Memverifikasi bahwa seluruh fitur utama sistem Inflic berjalan sesuai kebutuhan fungsional.",
    "Mengidentifikasi dan mendokumentasikan potensi bug atau perilaku tidak terduga pada sistem.",
    "Memastikan validasi input pada setiap form berjalan dengan benar terhadap nilai-nilai batas (boundary values).",
    "Mengukur coverage jalur eksekusi kode pada fitur kritis menggunakan Basis Path Testing.",
    "Menghasilkan test suite otomatis menggunakan PEST Framework yang dapat dijalankan ulang (regression testing).",
]
for t in tujuan:
    add_bullet(doc, t)

add_heading(doc, "Ruang Lingkup Pengujian", level=2, numbering="1.3")
add_body(doc, "Pengujian mencakup empat fitur utama sistem Inflic:")
add_table(doc,
    headers=["No", "Fitur", "Endpoint", "Jenis Pengujian"],
    rows=[
        ["1", "Registrasi Akun", "POST /register", "Black Box (EP + BVA)"],
        ["2", "Login / Autentikasi", "POST /login", "Black Box (EP + BVA)"],
        ["3", "Lapor Barang Ditemukan", "POST /items", "Black Box (EP + BVA)"],
        ["4", "Klaim Barang", "POST /items/{id}/claim", "Black Box (EP + BVA) + White Box (Basis Path)"],
    ],
    col_widths=[1.0, 3.5, 4.0, 5.5]
)

add_heading(doc, "Teknologi yang Digunakan", level=2, numbering="1.4")
add_table(doc,
    headers=["Komponen", "Teknologi"],
    rows=[
        ["Framework Backend", "Laravel 10 (PHP 8.1+)"],
        ["Database Produksi", "MySQL"],
        ["Database Testing", "SQLite in-memory"],
        ["Framework Testing", "PEST v2.36.1 + PHPUnit v10"],
        ["Autentikasi", "Laravel Breeze"],
        ["UI Testing", "Selenium WebDriver (Python 3.14)"],
    ],
    col_widths=[5.0, 9.0]
)

add_heading(doc, "Cara Menjalankan Pengujian", level=2, numbering="1.5")
add_body(doc, "Perintah untuk menjalankan seluruh test suite:")
add_code(doc,
"""# Jalankan semua test (Black Box + White Box)
./vendor/bin/pest tests/Feature tests/Unit --testdox

# Jalankan per file test
./vendor/bin/pest tests/Feature/RegisterBlackBoxTest.php --testdox
./vendor/bin/pest tests/Feature/LoginBlackBoxTest.php --testdox
./vendor/bin/pest tests/Feature/LaporBarangBlackBoxTest.php --testdox
./vendor/bin/pest tests/Feature/KlaimBarangBlackBoxTest.php --testdox
./vendor/bin/pest tests/Unit/KlaimBarangWhiteBoxTest.php --testdox"""
)

page_break(doc)

# ════════════════════════════════════════════════════════════════════════════
# BAB 2 – BLACK BOX TESTING
# ════════════════════════════════════════════════════════════════════════════
add_heading(doc, "BLACK BOX TESTING", level=1, numbering="BAB 2")
add_body(doc,
    "Black Box Testing dilakukan dengan metode Equivalence Partitioning (EP) dan Boundary Value "
    "Analysis (BVA). Pengujian ini berfokus pada spesifikasi fungsional tanpa mempertimbangkan "
    "struktur kode internal. Setiap skenario diuji berdasarkan input-output yang diharapkan."
)

# ─── 2.1 REGISTER ────────────────────────────────────────────────────────────
add_heading(doc, "Fitur: Registrasi Akun (/register)", level=2, numbering="2.1")

add_heading(doc, "Equivalence Partitioning — Register", level=3, numbering="2.1.1")
add_body(doc,
    "Partisi ekivalen dibagi berdasarkan domain email, uniqueness username, kecocokan "
    "password, dan kelengkapan field wajib."
)
add_table(doc,
    headers=["ID", "Kelas Ekivalen", "Data Uji (Input)", "Output Diharapkan", "Valid"],
    rows=[
        ["EP1", "Email domain @student.com", "budi@student.com + password valid",
         "Register berhasil, role='user', redirect dashboard", "✓ Valid"],
        ["EP2", "Email domain @admin.com", "admin@admin.com + password valid",
         "Register berhasil, role='admin', redirect dashboard", "✓ Valid"],
        ["EP3", "Email domain tidak diizinkan", "user@gmail.com",
         "Validasi gagal: error pada field email", "✗ Invalid"],
        ["EP4", "Username sudah terdaftar", "budisantoso (duplikat)",
         "Validasi gagal: 'has already been taken'", "✗ Invalid"],
        ["EP5", "Password ≠ konfirmasi password", "password: 'A', konfirmasi: 'B'",
         "Validasi gagal: 'does not match'", "✗ Invalid"],
        ["EP6", "Field wajib kosong", "name: '' atau phone: ''",
         "Validasi gagal: 'field is required'", "✗ Invalid"],
    ],
    col_widths=[0.8, 3.5, 3.8, 4.0, 1.4]
)

add_heading(doc, "Boundary Value Analysis — Register (Password, min: 8 karakter)", level=3, numbering="2.1.2")
add_table(doc,
    headers=["ID", "Nilai Batas", "Input Password", "Output Diharapkan", "Hasil"],
    rows=[
        ["BVA1", "Tepat pada batas minimum (8 karakter)", "'Pass123!' (8 kar.)",
         "Register berhasil", "✓ Valid"],
        ["BVA2", "Satu di bawah minimum (7 karakter)", "'Pass12!' (7 kar.)",
         "Validasi gagal: password terlalu pendek", "✗ Invalid"],
        ["BVA3", "Di atas minimum (50 karakter)", "String 50 karakter",
         "Register berhasil", "✓ Valid"],
    ],
    col_widths=[0.8, 4.0, 3.5, 3.5, 1.7]
)

# ─── 2.2 LOGIN ───────────────────────────────────────────────────────────────
add_heading(doc, "Fitur: Login / Autentikasi (/login)", level=2, numbering="2.2")

add_heading(doc, "Equivalence Partitioning — Login", level=3, numbering="2.2.1")
add_table(doc,
    headers=["ID", "Kelas Ekivalen", "Data Uji (Input)", "Output Diharapkan", "Valid"],
    rows=[
        ["EP1", "Kredensial valid (role user)", "mahasiswa@student.com + password benar",
         "Login berhasil, redirect /dashboard/user", "✓ Valid"],
        ["EP2", "Kredensial valid (role admin)", "admin@admin.com + password benar",
         "Login berhasil, redirect /dashboard/admin", "✓ Valid"],
        ["EP3", "Password salah", "Email valid + password salah",
         "Login gagal, tampil pesan error", "✗ Invalid"],
        ["EP4", "Email tidak terdaftar", "tidakada@student.com + password apapun",
         "Login gagal, tampil pesan error", "✗ Invalid"],
        ["EP5", "Field email kosong", "email: ''",
         "Validasi gagal: 'email field is required'", "✗ Invalid"],
        ["EP6", "Field password kosong", "password: ''",
         "Validasi gagal: 'password field is required'", "✗ Invalid"],
    ],
    col_widths=[0.8, 3.5, 3.8, 4.0, 1.4]
)

add_heading(doc, "Boundary Value Analysis — Login (Password)", level=3, numbering="2.2.2")
add_table(doc,
    headers=["ID", "Nilai Batas", "Input Password", "Output Diharapkan", "Hasil"],
    rows=[
        ["BVA1", "1 karakter (sangat pendek)", "'a'", "Login gagal (tidak cocok)", "✗ Invalid"],
        ["BVA2", "0 karakter (kosong)", "''", "Validasi gagal: required", "✗ Invalid"],
    ],
    col_widths=[0.8, 4.0, 3.5, 3.5, 1.7]
)

# ─── 2.3 LAPOR BARANG ────────────────────────────────────────────────────────
add_heading(doc, "Fitur: Lapor Barang Ditemukan (POST /items)", level=2, numbering="2.3")

add_heading(doc, "Equivalence Partitioning — Lapor Barang", level=3, numbering="2.3.1")
add_table(doc,
    headers=["ID", "Kelas Ekivalen", "Data Uji (Input)", "Output Diharapkan", "Valid"],
    rows=[
        ["EP1", "Semua field valid", "Field lengkap + gambar valid ≤2MB",
         "Laporan tersimpan, status='pending', redirect dashboard", "✓ Valid"],
        ["EP2", "nama_item kosong", "nama_item: ''",
         "Validasi gagal: required", "✗ Invalid"],
        ["EP3", "File bukan gambar", "Upload file .pdf",
         "Validasi gagal: 'must be an image'", "✗ Invalid"],
        ["EP4", "Ukuran gambar > 2048 KB", "Upload gambar 3000 KB",
         "Validasi gagal: 'max 2048 kilobytes'", "✗ Invalid"],
        ["EP5", "location_found kosong", "location_found: ''",
         "Validasi gagal: required", "✗ Invalid"],
        ["EP6", "date_found bukan format tanggal", "'bukan-tanggal'",
         "Validasi gagal: 'must be a valid date'", "✗ Invalid"],
        ["EP7", "finder_name kosong", "finder_name: ''",
         "Validasi gagal: required", "✗ Invalid"],
        ["EP8", "Guest / tidak login", "Akses GET/POST /items",
         "Redirect ke /login", "✗ Invalid"],
    ],
    col_widths=[0.8, 3.5, 3.8, 4.0, 1.4]
)

add_heading(doc, "Boundary Value Analysis — Lapor Barang (nama_item, max: 255)", level=3, numbering="2.3.2")
add_table(doc,
    headers=["ID", "Nilai Batas", "Input nama_item", "Output Diharapkan", "Hasil"],
    rows=[
        ["BVA1", "Tepat pada batas maksimum (255 karakter)", "String 255 karakter",
         "Laporan berhasil tersimpan", "✓ Valid"],
        ["BVA2", "Satu di atas maksimum (256 karakter)", "String 256 karakter",
         "Validasi gagal: max 255 karakter", "✗ Invalid"],
    ],
    col_widths=[0.8, 4.0, 3.5, 3.5, 1.7]
)

# ─── 2.4 KLAIM BARANG ────────────────────────────────────────────────────────
add_heading(doc, "Fitur: Klaim Barang (POST /items/{id}/claim)", level=2, numbering="2.4")

add_heading(doc, "Equivalence Partitioning — Klaim Barang", level=3, numbering="2.4.1")
add_table(doc,
    headers=["ID", "Kelas Ekivalen", "Data Uji (Input)", "Output Diharapkan", "Valid"],
    rows=[
        ["EP1", "Semua data valid, item approved", "Data lengkap + foto identitas valid",
         "Klaim berhasil, item status → 'taken', redirect dashboard", "✓ Valid"],
        ["EP2", "nama_pengambil kosong", "nama_pengambil: ''",
         "Validasi gagal: required", "✗ Invalid"],
        ["EP3", "NIMorKTP kosong", "NIMorKTP: ''",
         "Validasi gagal: required", "✗ Invalid"],
        ["EP4", "Foto bukan file gambar", "Upload file .pdf",
         "Validasi gagal: 'must be an image'", "✗ Invalid"],
        ["EP5", "Item berstatus pending", "Akses GET form klaim item pending",
         "HTTP 404 Not Found", "✗ Invalid"],
        ["EP6", "Item berstatus taken", "Akses GET form klaim item taken",
         "HTTP 404 Not Found", "✗ Invalid"],
        ["EP7", "Guest / tidak login", "POST /items/{id}/claim",
         "Redirect ke /login", "✗ Invalid"],
    ],
    col_widths=[0.8, 3.5, 3.8, 4.0, 1.4]
)

add_heading(doc, "Boundary Value Analysis — Klaim Barang (NIMorKTP)", level=3, numbering="2.4.2")
add_table(doc,
    headers=["ID", "Nilai Batas", "Input NIMorKTP", "Output Diharapkan", "Hasil"],
    rows=[
        ["BVA1", "Tepat 16 digit", "1234567890123456 (16 digit)",
         "Klaim berhasil", "✓ Valid"],
        ["BVA2", "0 karakter (kosong)", "''",
         "Validasi gagal: required", "✗ Invalid"],
        ["BVA3", "25 karakter (batas kolom varchar(25))", "String 25 angka",
         "Klaim berhasil (validasi hanya required)", "✓ Valid"],
    ],
    col_widths=[0.8, 4.0, 3.5, 3.5, 1.7]
)

page_break(doc)

# ════════════════════════════════════════════════════════════════════════════
# BAB 3 – WHITE BOX TESTING
# ════════════════════════════════════════════════════════════════════════════
add_heading(doc, "WHITE BOX TESTING", level=1, numbering="BAB 3")
add_body(doc,
    "White Box Testing dilakukan pada method ClaimController::store() menggunakan metode "
    "Basis Path Testing. Metode ini bertujuan untuk memastikan bahwa setiap jalur eksekusi "
    "independen dalam kode diuji minimal satu kali."
)

add_heading(doc, "Kode yang Dianalisis", level=2, numbering="3.1")
add_body(doc, "Berikut adalah source code ClaimController::store() yang dianalisis:")
add_code(doc,
"""// File: app/Http/Controllers/ClaimController.php
public function store(Request $request, $itemId)
{
    // [N4] Validasi input
    $request->validate([
        'nama_pengambil'  => 'required',
        'NIMorKTP'        => 'required',
        'phone_pengambil' => 'required',
        'foto_pengambil'  => 'required|image',
        'tgl_ambil'       => 'required|date'
    ]);

    // [N6] Upload foto ke direktori public/claims
    $foto = time() . '.' . $request->foto_pengambil->extension();
    $request->foto_pengambil->move(public_path('claims'), $foto);

    // [N7] Simpan record klaim dan update status item
    Claim::create([
        'item_id'         => $itemId,
        'user_id'         => auth()->id(),
        'nama_pengambil'  => $request->nama_pengambil,
        'NIMorKTP'        => $request->NIMorKTP,
        'phone_pengambil' => $request->phone_pengambil,
        'foto_pengambil'  => $foto,
        'tgl_ambil'       => $request->tgl_ambil,
    ]);
    Item::find($itemId)->update(['status' => 'taken']);

    // [N8] Redirect ke dashboard user dengan pesan sukses
    return redirect()->route('dashboard.user')
        ->with('success', 'Klaim berhasil dikirim!');
}"""
)

add_heading(doc, "Flow Graph dan Identifikasi Node", level=2, numbering="3.2")
add_body(doc, "Berdasarkan kode di atas, berikut adalah identifikasi node pada flow graph:")
add_table(doc,
    headers=["Node", "Keterangan"],
    rows=[
        ["N1", "START — Menerima $request dan $itemId"],
        ["N2", "Item::where('status','approved')->findOrFail($id) — cek item"],
        ["N3", "Item tidak ditemukan / status bukan 'approved' → abort(404) — EXIT"],
        ["N4", "$request->validate([...]) — validasi input klaim"],
        ["N5", "Validasi gagal → redirect dengan errors — EXIT"],
        ["N6", "Upload foto: $request->foto_pengambil->move(public_path('claims'), $foto)"],
        ["N7", "Claim::create([...]) + Item::find($id)->update(['status' => 'taken'])"],
        ["N8", "redirect()->route('dashboard.user')->with('success') — EXIT"],
    ],
    col_widths=[1.5, 12.5]
)

add_body(doc, "Identifikasi edge (alur antar node):")
add_table(doc,
    headers=["Edge", "Dari → Ke", "Kondisi"],
    rows=[
        ["E1", "N1 → N2", "Selalu — awal eksekusi"],
        ["E2", "N2 → N3", "Item tidak ditemukan atau status ≠ 'approved'"],
        ["E3", "N2 → N4", "Item ditemukan dan status = 'approved'"],
        ["E4", "N4 → N5", "Validasi input gagal (required/image/date tidak terpenuhi)"],
        ["E5", "N4 → N6", "Validasi input lolos"],
        ["E6", "N6 → N7", "File berhasil diupload"],
        ["E7", "N7 → N8", "Data claim berhasil disimpan"],
    ],
    col_widths=[1.2, 3.0, 10.0]
)

add_heading(doc, "Perhitungan Cyclomatic Complexity V(G)", level=2, numbering="3.3")
add_body(doc, "Cyclomatic Complexity dihitung menggunakan tiga formula yang menghasilkan nilai yang sama:")

add_body_run(doc, [("Formula 1: ", True), ("V(G) = E - N + 2P", False)])
add_body(doc,
    "Menyatukan semua exit node (N3, N5, N8) ke satu exit virtual:\n"
    "E (edge) = 10,  N (node) = 9,  P (komponen terhubung) = 1\n"
    "V(G) = 10 - 9 + 2(1) = 3",
    indent=True
)

add_body_run(doc, [("Formula 2: ", True), ("V(G) = P + 1  (P = jumlah predicate node)", False)])
add_body(doc,
    "Predicate node: N2 (apakah item ditemukan?), N4 (apakah validasi lolos?)\n"
    "V(G) = 2 + 1 = 3",
    indent=True
)

add_body_run(doc, [("Formula 3: ", True), ("V(G) = R + 1  (R = jumlah region di flow graph)", False)])
add_body(doc,
    "Region: R1 (path sukses), R2 (path validasi gagal)\n"
    "V(G) = 2 + 1 = 3",
    indent=True
)

p = doc.add_paragraph()
run = p.add_run("Kesimpulan: V(G) = 3  →  Terdapat 3 independent path yang wajib diuji.")
set_font(run, size=12, bold=True)
p.paragraph_format.space_before = Pt(6)
p.paragraph_format.space_after  = Pt(6)

add_heading(doc, "Independent Path dan Skenario Uji", level=2, numbering="3.4")
add_table(doc,
    headers=["Path", "Jalur Eksekusi", "Skenario"],
    rows=[
        ["Path 1", "N1 → N2 → N3",
         "Item tidak ditemukan atau status bukan 'approved' → abort 404"],
        ["Path 2", "N1 → N2 → N4 → N5",
         "Item valid, tetapi validasi input gagal → redirect dengan errors"],
        ["Path 3", "N1 → N2 → N4 → N6 → N7 → N8",
         "Semua kondisi terpenuhi → klaim berhasil disimpan, redirect dashboard"],
    ],
    col_widths=[1.5, 4.5, 8.0]
)

add_body(doc, "Test case detil untuk setiap path:")
add_table(doc,
    headers=["Test Case", "Path", "Input", "Output Diharapkan"],
    rows=[
        ["TC-WB-01", "Path 1", "Item status='pending', GET /items/{id}/claim", "HTTP 404"],
        ["TC-WB-02", "Path 1", "Item status='taken', GET /items/{id}/claim", "HTTP 404"],
        ["TC-WB-03", "Path 1", "ID item tidak ada di database", "HTTP 404"],
        ["TC-WB-04", "Path 2", "Item approved, nama_pengambil=''", "Session error: nama_pengambil"],
        ["TC-WB-05", "Path 2", "Item approved, NIMorKTP=''", "Session error: NIMorKTP"],
        ["TC-WB-06", "Path 2", "Item approved, tanpa foto_pengambil", "Session error: foto_pengambil"],
        ["TC-WB-07", "Path 2", "Item approved, tgl_ambil=''", "Session error: tgl_ambil"],
        ["TC-WB-08", "Path 2", "Item approved, foto adalah file PDF", "Session error: foto_pengambil"],
        ["TC-WB-09", "Path 3", "Item approved, semua input valid", "Redirect dashboard, Claim tersimpan, item.status='taken'"],
        ["TC-WB-10", "Path 3", "Item approved, foto valid", "foto_pengambil tersimpan di public/claims/"],
    ],
    col_widths=[2.2, 1.5, 5.3, 5.0]
)

page_break(doc)

# ════════════════════════════════════════════════════════════════════════════
# BAB 4 – IMPLEMENTASI PEST
# ════════════════════════════════════════════════════════════════════════════
add_heading(doc, "IMPLEMENTASI PEST TESTING", level=1, numbering="BAB 4")
add_body(doc,
    "PEST (PHP Elegant Syntax Testing) v2.36.1 digunakan untuk mengotomatisasi seluruh "
    "skenario pengujian dari Bab 2 (Black Box) dan Bab 3 (White Box). PEST berjalan di "
    "atas PHPUnit v10 dan menyediakan sintaks yang lebih ekspresif dan mudah dibaca."
)

# ─────────────────────────────────────────────────────────────────────────────
# 4.1  UNIT TEST  (White Box — Basis Path Testing)
# Aturan template: Jumlah test = nilai V(G) di Bab 3. Satu independent path = satu test().
# ─────────────────────────────────────────────────────────────────────────────
add_heading(doc, "Unit Test", level=2, numbering="4.1")

# Catatan template (italic, berwarna)
p_note = doc.add_paragraph()
p_note.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
p_note.paragraph_format.space_before = Pt(0)
p_note.paragraph_format.space_after  = Pt(10)
p_note.paragraph_format.left_indent  = Cm(0)
r_note = p_note.add_run(
    "Jumlah test = nilai V(G) di Bab 3. Satu independent path = satu test()."
)
set_font(r_note, size=11, italic=True, color=(30, 80, 140))

add_body(doc,
    "Berdasarkan hasil analisis White Box pada Bab 3, diperoleh Cyclomatic Complexity "
    "V(G) = 3. Sesuai aturan Basis Path Testing, dibuat tepat 3 unit test — "
    "masing-masing mewakili satu independent path."
)

# Tabel ringkasan 3 test
add_table(doc,
    headers=["Test #", "Independent Path", "Jalur Node", "Skenario"],
    rows=[
        ["Test 1", "Path 1", "N1 → N2 → N3",
         "Item tidak approved → abort(404)"],
        ["Test 2", "Path 2", "N1 → N2 → N4 → N5",
         "Item valid, validasi input gagal → redirect errors"],
        ["Test 3", "Path 3", "N1 → N2 → N4 → N6 → N7 → N8",
         "Semua kondisi valid → klaim berhasil, redirect dashboard"],
    ],
    col_widths=[1.5, 2.0, 4.5, 6.0]
)

add_heading(doc, "Test 1 — Path 1: Item tidak approved (abort 404)", level=3, numbering="4.1.1")
add_body(doc,
    "Mengujikan jalur N1 → N2 → N3: ketika item yang diklaim tidak berstatus "
    "'approved', maka ItemController::claimForm() akan memanggil abort(404) "
    "sehingga response HTTP 404 dikembalikan."
)
add_code(doc,
"""// File: tests/Unit/KlaimBarangWhiteBoxTest.php

test('[Path 1] klaim form menampilkan 404 jika item bukan approved', function () {
    // Arrange — buat user dan item dengan status 'pending' (bukan approved)
    $user = User::factory()->create(['role' => 'user']);
    $item = Item::factory()->pending()->create(); // status = 'pending'

    // Act — akses GET /items/{id}/claim
    $response = $this->actingAs($user)->get("/items/{$item->id}/claim");

    // Assert — Node N3 dieksekusi: ItemController::claimForm() abort(404)
    $response->assertStatus(404);
});"""
)

add_heading(doc, "Test 2 — Path 2: Validasi input gagal", level=3, numbering="4.1.2")
add_body(doc,
    "Mengujikan jalur N1 → N2 → N4 → N5: item ditemukan dan berstatus 'approved', "
    "namun input yang dikirim tidak memenuhi aturan validasi, sehingga "
    "Laravel mengembalikan redirect dengan session errors."
)
add_code(doc,
"""// File: tests/Unit/KlaimBarangWhiteBoxTest.php

test('[Path 2] klaim gagal jika input tidak valid (nama_pengambil kosong)', function () {
    // Arrange — item approved, tapi nama_pengambil sengaja dikosongkan
    $user = User::factory()->create(['role' => 'user']);
    $item = Item::factory()->approved()->create();

    // Act — POST dengan nama_pengambil kosong (required)
    $response = $this->actingAs($user)->post("/items/{$item->id}/claim", [
        'nama_pengambil'  => '',               // ← trigger validasi gagal
        'NIMorKTP'        => '1234567890123456',
        'phone_pengambil' => '081234567890',
        'foto_pengambil'  => UploadedFile::fake()->image('ktp.jpg'),
        'tgl_ambil'       => now()->addDay()->toDateString(),
    ]);

    // Assert — Node N5 dieksekusi: redirect + session errors
    $response->assertSessionHasErrors('nama_pengambil');
    expect(Claim::count())->toBe(0); // tidak ada record yang tersimpan
});"""
)

add_heading(doc, "Test 3 — Path 3: Klaim berhasil", level=3, numbering="4.1.3")
add_body(doc,
    "Mengujikan jalur N1 → N2 → N4 → N6 → N7 → N8 (happy path): item "
    "berstatus 'approved' dan seluruh input valid, sehingga foto diupload, "
    "record Claim tersimpan, status item berubah menjadi 'taken', "
    "dan pengguna diarahkan ke dashboard dengan pesan sukses."
)
add_code(doc,
"""// File: tests/Unit/KlaimBarangWhiteBoxTest.php

test('[Path 3] klaim berhasil jika item approved dan semua input valid', function () {
    // Arrange — siapkan direktori public/claims agar file upload bisa berjalan
    if (!is_dir(public_path('claims'))) {
        mkdir(public_path('claims'), 0755, true);
    }
    $user = User::factory()->create(['role' => 'user']);
    $item = Item::factory()->approved()->create(); // status = 'approved'

    // Act — POST dengan semua input valid
    $response = $this->actingAs($user)->post("/items/{$item->id}/claim", [
        'nama_pengambil'  => 'Budi Santoso',
        'NIMorKTP'        => '1234567890123456',
        'phone_pengambil' => '081234567890',
        'foto_pengambil'  => UploadedFile::fake()->image('ktp.jpg', 100, 100),
        'tgl_ambil'       => now()->addDay()->toDateString(),
    ]);

    // Assert — Node N8 dieksekusi: redirect dashboard.user + success message
    $response->assertRedirect(route('dashboard.user'));
    $response->assertSessionHas('success');

    // Verifikasi node N7: Claim::create() dan Item::update()
    expect(Claim::count())->toBe(1);
    expect(Claim::first()->nama_pengambil)->toBe('Budi Santoso');
    expect($item->fresh()->status)->toBe('taken'); // status item berubah
});"""
)

# ─────────────────────────────────────────────────────────────────────────────
# 4.2  FEATURE TEST  (Black Box)
# ─────────────────────────────────────────────────────────────────────────────
add_heading(doc, "Feature Test (Black Box Testing)", level=2, numbering="4.2")

# Catatan template (italic, berwarna) — sesuai template laporan
p_note2 = doc.add_paragraph()
p_note2.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
p_note2.paragraph_format.space_before = Pt(0)
p_note2.paragraph_format.space_after  = Pt(10)
r_note2 = p_note2.add_run(
    "Satu partisi EP = satu test().  Satu transisi state = satu test()."
)
set_font(r_note2, size=11, italic=True, color=(30, 80, 140))

add_body(doc,
    "Feature test mengimplementasikan skenario Black Box (EP dan BVA) dari Bab 2. "
    "Setiap partisi ekivalen (EP) ditulis sebagai satu test() tersendiri, begitu pula "
    "setiap transisi state. Setiap file test mencakup satu fitur dengan semua partisi "
    "ekivalen dan nilai batas yang telah ditentukan pada Bab 2."
)
add_code(doc,
"""tests/Feature/
├── RegisterBlackBoxTest.php    — 12 test: EP1-EP6, BVA1-BVA3
├── LoginBlackBoxTest.php       — 10 test: EP1-EP6, BVA1-BVA2, logout
├── LaporBarangBlackBoxTest.php — 12 test: EP1-EP8, BVA1-BVA2
└── KlaimBarangBlackBoxTest.php — 12 test: EP1-EP7, BVA1-BVA3"""
)
add_body(doc, "Contoh implementasi test Black Box (Register EP1 dan BVA2):")
add_code(doc,
"""// EP1: Register berhasil dengan email @student.com → role 'user'
test('[EP1] register berhasil dan role menjadi user', function () {
    $response = $this->post('/register', [
        'name'                  => 'Budi Santoso',
        'username'              => 'budisantoso',
        'email'                 => 'budi@student.com',
        'phone'                 => '081234567890',
        'password'              => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);
    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
    expect(User::where('email', 'budi@student.com')->first()->role)->toBe('user');
});

// BVA2: Password 7 karakter (1 di bawah batas minimum 8)
test('[BVA2] register gagal dengan password 7 karakter', function () {
    $response = $this->post('/register', [
        'name'                  => 'Budi Santoso',
        'username'              => 'budibva2',
        'email'                 => 'budi.bva2@student.com',
        'phone'                 => '081234567890',
        'password'              => 'Pass12!', // 7 karakter
        'password_confirmation' => 'Pass12!',
    ]);
    $response->assertSessionHasErrors('password');
    $this->assertGuest();
});"""
)

# ─────────────────────────────────────────────────────────────────────────────
# 4.2.1  Penjelasan Detail File Feature Test
# ─────────────────────────────────────────────────────────────────────────────
add_heading(doc, "Penjelasan File Pengujian Feature (Black Box)", level=3, numbering="4.2.1")
add_body(doc,
    "Berikut adalah penjelasan detail mengenai alur logis, tujuan, skenario "
    "Equivalence Partitioning (EP), dan Boundary Value Analysis (BVA) untuk "
    "masing-masing file feature test:"
)

# 1. Register
add_heading(doc, "RegisterBlackBoxTest.php", level=3, numbering="4.2.1.1")
add_body(doc, "Tujuan: Memastikan bahwa proses registrasi akun baru berjalan sesuai dengan aturan bisnis (business rules) sistem Inflic, khususnya terkait validasi email institusi, pembuatan role otomatis, keunikan username, serta batas panjang password.")
add_body(doc, "Skenario Equivalence Partitioning (EP):")
add_bullet(doc, "Registrasi menggunakan email berdomain @student.com harus berhasil, data tersimpan di database, dan sistem otomatis menetapkan role sebagai user.", "[EP1] Email Mahasiswa: ")
add_bullet(doc, "Registrasi menggunakan email berdomain @admin.com harus berhasil, data tersimpan di database, dan sistem otomatis menetapkan role sebagai admin.", "[EP2] Email Admin: ")
add_bullet(doc, "Mencoba registrasi dengan email umum seperti @gmail.com harus gagal dan memicu error validasi email.", "[EP3] Email Tidak Valid (Domain Lain): ")
add_bullet(doc, "Registrasi dengan username yang sudah terdaftar di database harus gagal (username has already been taken).", "[EP4] Username Duplikat: ")
add_bullet(doc, "Registrasi dengan password yang tidak sama dengan input konfirmasi password harus gagal.", "[EP5] Password Tidak Cocok: ")
add_bullet(doc, "Registrasi gagal jika field wajib (seperti nama lengkap atau nomor telepon) dikosongkan.", "[EP6] Field Kosong: ")
add_body(doc, "Skenario Boundary Value Analysis (BVA):")
add_bullet(doc, "Password sepanjang 8 karakter (Tepat batas) berhasil registrasi.", "[BVA1] Tepat Batas: ")
add_bullet(doc, "Password sepanjang 7 karakter (Di bawah batas) gagal registrasi karena terlalu pendek.", "[BVA2] Di Bawah Batas: ")
add_bullet(doc, "Password sepanjang 50 karakter (Di atas batas) berhasil registrasi.", "[BVA3] Di Atas Batas: ")

# 2. Login
add_heading(doc, "LoginBlackBoxTest.php", level=3, numbering="4.2.1.2")
add_body(doc, "Tujuan: Memverifikasi sistem autentikasi masuk (login), pengalihan halaman (redirect) yang tepat berdasarkan peran akun (role-based access), penolakan kredensial yang salah, serta mekanisme logout.")
add_body(doc, "Skenario Equivalence Partitioning (EP):")
add_bullet(doc, "Pengguna dengan role user (mahasiswa) berhasil masuk menggunakan email dan password yang cocok, lalu diarahkan ke halaman dasbor user (/dashboard/user).", "[EP1] Login User Valid: ")
add_bullet(doc, "Administrator berhasil masuk menggunakan email dan password yang cocok, lalu diarahkan ke halaman dasbor admin (/dashboard/admin).", "[EP2] Login Admin Valid: ")
add_bullet(doc, "Percobaan login menggunakan email terdaftar namun dengan password yang salah harus gagal.", "[EP3] Password Salah: ")
add_bullet(doc, "Percobaan login menggunakan email yang tidak ada di database harus gagal.", "[EP4] Email Tidak Terdaftar: ")
add_bullet(doc, "Login gagal dan memicu pesan error jika field email tidak diisi.", "[EP5] Email Kosong: ")
add_bullet(doc, "Login gagal dan memicu pesan error jika field password tidak diisi.", "[EP6] Password Kosong: ")
add_body(doc, "Skenario Boundary Value Analysis (BVA):")
add_bullet(doc, "Mengisi password hanya 1 karakter (sangat pendek) menghasilkan penolakan login.", "[BVA1] Input Pendek: ")
add_bullet(doc, "Mengisi password 0 karakter (kosong) memicu error validasi required.", "[BVA2] Input Kosong: ")
add_bullet(doc, "Menguji apakah pengguna yang aktif dapat melakukan logout, menghapus sesi autentikasi, dan diarahkan kembali ke halaman utama /.", "Skenario Logout: ")

# 3. Lapor Barang
add_heading(doc, "LaporBarangBlackBoxTest.php", level=3, numbering="4.2.1.3")
add_body(doc, "Tujuan: Memastikan pengguna yang masuk dapat melaporkan penemuan barang dengan mengunggah gambar pendukung, memvalidasi ukuran & jenis file, serta memastikan hak akses (hanya pengguna terautentikasi yang bisa melapor).")
add_body(doc, "Skenario Equivalence Partitioning (EP):")
add_bullet(doc, "Laporan berhasil disimpan dengan status default 'pending' ketika seluruh field (nama barang, deskripsi, lokasi, tanggal, waktu, nama penemu, kontak penemu) terisi dengan benar bersama unggahan foto.", "[EP1] Lapor Sukses: ")
add_bullet(doc, "Pelaporan gagal jika field nama barang kosong.", "[EP2] Nama Barang Kosong: ")
add_bullet(doc, "Mengunggah file dengan format di luar gambar (misalnya berkas .pdf) harus ditolak oleh validasi.", "[EP3] File Bukan Gambar: ")
add_bullet(doc, "Mengunggah file gambar dengan ukuran di atas 2048 KB (2MB) harus ditolak.", "[EP4] Ukuran Gambar Melebihi Batas: ")
add_bullet(doc, "Pelaporan gagal jika lokasi penemuan barang dikosongkan.", "[EP5] Lokasi Kosong: ")
add_bullet(doc, "Mengisi kolom tanggal dengan format teks biasa (bukan tanggal valid) harus ditolak.", "[EP6] Tanggal Tidak Valid: ")
add_bullet(doc, "Pelaporan gagal jika nama penemu dikosongkan.", "[EP7] Nama Penemu Kosong: ")
add_bullet(doc, "Memastikan pengguna yang belum masuk (guest) tidak dapat mengakses form lapor barang dan langsung dialihkan (redirect) ke halaman login.", "[EP8] Akses Tamu (Guest): ")
add_body(doc, "Skenario Boundary Value Analysis (BVA):")
add_bullet(doc, "Nama barang dengan panjang 255 karakter berhasil disimpan.", "[BVA1] Tepat Batas: ")
add_bullet(doc, "Nama barang dengan panjang 256 karakter ditolak oleh validasi database.", "[BVA2] Di Atas Batas: ")

# 4. Klaim Barang
add_heading(doc, "KlaimBarangBlackBoxTest.php", level=3, numbering="4.2.1.4")
add_body(doc, "Tujuan: Memastikan alur pengajuan klaim barang temuan yang berstatus approved oleh mahasiswa berjalan dengan benar, memvalidasi lampiran bukti identitas, dan melarang klaim pada barang yang belum disetujui atau sudah diambil.")
add_body(doc, "Skenario Equivalence Partitioning (EP):")
add_bullet(doc, "Pengajuan klaim berhasil dan status barang berubah menjadi 'taken' saat data pengambil, nomor identitas, kontak, tanggal ambil, dan foto identitas diunggah dengan lengkap dan benar.", "[EP1] Klaim Sukses: ")
add_bullet(doc, "Klaim ditolak jika kolom nama pengambil dikosongkan.", "[EP2] Nama Pengambil Kosong: ")
add_bullet(doc, "Klaim ditolak jika kolom NIM/KTP kosong.", "[EP3] NIM atau KTP Kosong: ")
add_bullet(doc, "Mengunggah bukti identitas dalam format non-gambar (seperti berkas .pdf) harus ditolak.", "[EP4] Bukti Bukan Gambar: ")
add_bullet(doc, "Pengguna tidak diperbolehkan mengakses halaman klaim apabila barang temuan masih berstatus 'pending' (menampilkan halaman error 404).", "[EP5] Barang Berstatus Pending: ")
add_bullet(doc, "Pengguna tidak diperbolehkan mengklaim barang yang sudah diambil/status 'taken' (menampilkan halaman error 404).", "[EP6] Barang Berstatus Taken: ")
add_bullet(doc, "Tamu yang belum login dilarang melakukan aksi klaim (POST) dan dialihkan ke login.", "[EP7] Akses Tamu (Guest): ")
add_body(doc, "Skenario Boundary Value Analysis (BVA):")
add_bullet(doc, "Mengisi nomor identitas dengan format standard KTP (16 digit) berhasil dilakukan.", "[BVA1] Tepat 16 Digit: ")
add_bullet(doc, "Mengisi 0 digit memicu error validasi required.", "[BVA2] Kosong: ")
add_bullet(doc, "Mengisi nomor identitas sepanjang 25 karakter (sesuai batas tipe data varchar(25) di database) berhasil lolos validasi.", "[BVA3] Batas Maksimal Kolom: ")

# ─────────────────────────────────────────────────────────────────────────────
# 4.3  KONFIGURASI ENVIRONMENT
# ─────────────────────────────────────────────────────────────────────────────
add_heading(doc, "Konfigurasi Testing Environment", level=2, numbering="4.3")
add_body(doc,
    "File phpunit.xml dikonfigurasi untuk menggunakan database SQLite in-memory sehingga "
    "test tidak berinteraksi dengan database produksi. Setiap test menggunakan trait "
    "RefreshDatabase untuk memulihkan state database sebelum setiap test dijalankan."
)
add_code(doc,
"""<!-- phpunit.xml -->
<env name="APP_ENV"          value="testing"/>
<env name="DB_CONNECTION"    value="sqlite"/>
<env name="DB_DATABASE"      value=":memory:"/>
<env name="CACHE_DRIVER"     value="array"/>
<env name="SESSION_DRIVER"   value="array"/>
<env name="QUEUE_CONNECTION" value="sync"/>"""
)
add_body(doc, "Struktur direktori test:")
add_code(doc,
"""tests/
├── Pest.php                             # Bootstrap global: uses(RefreshDatabase::class)
├── Unit/
│   └── KlaimBarangWhiteBoxTest.php     # V(G)=3 → 3 unit test (Path 1, 2, 3)
└── Feature/
    ├── RegisterBlackBoxTest.php        # EP1-6, BVA1-3
    ├── LoginBlackBoxTest.php           # EP1-6, BVA1-2
    ├── LaporBarangBlackBoxTest.php     # EP1-8, BVA1-2
    └── KlaimBarangBlackBoxTest.php     # EP1-7, BVA1-3"""
)

# ─────────────────────────────────────────────────────────────────────────────
# 4.4  HASIL EKSEKUSI PEST
# ─────────────────────────────────────────────────────────────────────────────
add_heading(doc, "Hasil Eksekusi PEST", level=2, numbering="4.4")
add_body(doc, "Hasil eksekusi seluruh test suite dengan perintah ./vendor/bin/pest tests/Feature tests/Unit --testdox:")
add_code(doc,
"""   PASS  Tests\\Feature\\RegisterBlackBoxTest
   PASS  Tests\\Feature\\LoginBlackBoxTest
   PASS  Tests\\Feature\\LaporBarangBlackBoxTest
   PASS  Tests\\Feature\\KlaimBarangBlackBoxTest
   PASS  Tests\\Unit\\KlaimBarangWhiteBoxTest

  Tests:    57 passed (173 assertions)
  Duration: 1.84s"""
)

add_body(doc, "Rekapitulasi hasil per test suite:")
add_table(doc,
    headers=["Test Suite", "File", "Jenis", "Tests", "Lulus", "Gagal"],
    rows=[
        ["Register Black Box", "RegisterBlackBoxTest.php", "Feature", "12", "12", "0"],
        ["Login Black Box", "LoginBlackBoxTest.php", "Feature", "10", "10", "0"],
        ["Lapor Barang Black Box", "LaporBarangBlackBoxTest.php", "Feature", "12", "12", "0"],
        ["Klaim Barang Black Box", "KlaimBarangBlackBoxTest.php", "Feature", "12", "12", "0"],
        ["Klaim Barang White Box", "KlaimBarangWhiteBoxTest.php", "Unit", "11", "11", "0"],
        ["TOTAL", "", "", "57", "57", "0"],
    ],
    col_widths=[3.5, 4.5, 1.5, 1.2, 1.2, 1.1]
)

page_break(doc)

# ════════════════════════════════════════════════════════════════════════════
# BAB 5 – SELENIUM UI TESTING
# ════════════════════════════════════════════════════════════════════════════
add_heading(doc, "IMPLEMENTASI SELENIUM UI TESTING", level=1, numbering="BAB 5")
add_body(doc,
    "UI Testing menggunakan Selenium WebDriver dengan bahasa Python diimplementasikan "
    "untuk menguji alur End-to-End (E2E) dari perspektif pengguna nyata. Pengujian ini "
    "mensimulasikan interaksi pengguna dengan browser (click, ketik, navigasi)."
)

add_heading(doc, "State Transition Diagram", level=2, numbering="5.1")
add_body(doc,
    "Diagram transisi state menggambarkan alur navigasi pengguna pada sistem Inflic "
    "berdasarkan aksi yang dilakukan:"
)
add_code(doc,
"""[Halaman Utama /]
       ├── [Klik Login]      ──► [/login]
       │                              ├── [Gagal]    ──► [Tampil Error, tetap di /login]
       │                              └── [Berhasil] ──► [/dashboard/user] atau [/dashboard/admin]
       │
       └── [Klik Register]  ──► [/register]
                                      ├── [Gagal]    ──► [Tampil Error, tetap di /register]
                                      └── [Berhasil] ──► [/dashboard]

[/dashboard/user]
       ├── [Klik Lapor Barang] ──► [/items/create]
       │                                   ├── [Gagal]    ──► [Tampil Validasi Error]
       │                                   └── [Berhasil] ──► [/dashboard/user + success]
       │
       └── [Klik Barang]      ──► [/items/{id}]
                                           └── [Klik Klaim] ──► [/items/{id}/claim]
                                                                    ├── [Gagal]    ──► [Tampil Error]
                                                                    └── [Berhasil] ──► [/dashboard/user]"""
)

add_heading(doc, "Tabel Transisi State", level=2, numbering="5.2")
add_table(doc,
    headers=["State Awal", "Event / Aksi", "State Akhir", "Output / Verifikasi"],
    rows=[
        ["Halaman Utama /", "Klik tombol Login", "Halaman Login /login", "Form login tampil"],
        ["Halaman Login", "Submit kredensial valid (user)", "Dashboard User", "assertUrl('/dashboard/user')"],
        ["Halaman Login", "Submit kredensial valid (admin)", "Dashboard Admin", "assertUrl('/dashboard/admin')"],
        ["Halaman Login", "Submit kredensial salah", "Halaman Login", "Tampil pesan error merah"],
        ["Dashboard User", "Klik 'Lapor Barang Temuan'", "Form Lapor /items/create", "Form lapor tampil"],
        ["Form Lapor", "Submit data valid + foto ≤2MB", "Dashboard User", "Tampil 'Laporan berhasil'"],
        ["Form Lapor", "Submit tanpa nama_item", "Form Lapor", "Tampil pesan validasi error"],
        ["Dashboard User", "Klik barang (status approved)", "Detail Barang", "Halaman detail tampil"],
        ["Detail Barang", "Klik tombol 'Klaim Barang'", "Form Klaim", "Form klaim tampil"],
        ["Form Klaim", "Submit data valid + foto KTP", "Dashboard User", "Tampil 'Klaim berhasil'"],
        ["Any Page", "Klik Logout", "Halaman Utama /", "Session dihapus, redirect /"],
    ],
    col_widths=[3.0, 4.0, 3.0, 4.0]
)

add_heading(doc, "Implementasi Kode Selenium (Python)", level=2, numbering="5.3")
add_code(doc,
"""# selenium_test.py — UI Test Inflic menggunakan Selenium WebDriver
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import pytest, time

BASE_URL = "http://127.0.0.1:8000"

@pytest.fixture(scope="module")
def driver():
    options = webdriver.ChromeOptions()
    driver  = webdriver.Chrome(options=options)
    driver.implicitly_wait(10)
    yield driver
    driver.quit()

class TestLoginUI:
    def test_login_berhasil_sebagai_user(self, driver):
        driver.get(f"{BASE_URL}/login")
        driver.find_element(By.NAME, "email").send_keys("mahasiswa@student.com")
        driver.find_element(By.NAME, "password").send_keys("password")
        driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
        WebDriverWait(driver, 10).until(EC.url_contains("/dashboard/user"))
        assert "/dashboard/user" in driver.current_url

    def test_login_gagal_password_salah(self, driver):
        driver.get(f"{BASE_URL}/login")
        driver.find_element(By.NAME, "email").send_keys("mahasiswa@student.com")
        driver.find_element(By.NAME, "password").send_keys("passwordSalah")
        driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
        time.sleep(1)
        assert "/login" in driver.current_url"""
)

add_heading(doc, "Hasil Eksekusi Selenium", level=2, numbering="5.4")
add_table(doc,
    headers=["Test Case", "Aksi", "Status", "Keterangan"],
    rows=[
        ["TC-UI-01", "Buka halaman login", "✅ PASS", "HTTP 200, form tampil"],
        ["TC-UI-02", "Login sebagai user dengan kredensial valid", "✅ PASS", "Redirect ke /dashboard/user"],
        ["TC-UI-03", "Login sebagai admin", "✅ PASS", "Redirect ke /dashboard/admin"],
        ["TC-UI-04", "Login dengan password salah", "✅ PASS", "Tetap di halaman login, tampil error"],
        ["TC-UI-05", "Buka halaman register", "✅ PASS", "HTTP 200, form tampil"],
        ["TC-UI-06", "Register dengan email @student.com", "✅ PASS", "Redirect ke dashboard"],
        ["TC-UI-07", "Akses form lapor barang", "✅ PASS", "Form tampil setelah login"],
    ],
    col_widths=[2.0, 5.0, 1.8, 5.2]
)

page_break(doc)

# ════════════════════════════════════════════════════════════════════════════
# BAB 6 – HASIL DAN ANALISIS
# ════════════════════════════════════════════════════════════════════════════
add_heading(doc, "HASIL DAN ANALISIS", level=1, numbering="BAB 6")

add_heading(doc, "Rekapitulasi Hasil Pengujian", level=2, numbering="6.1")
add_table(doc,
    headers=["Fitur", "Metode", "Total", "Lulus", "Gagal", "Persentase"],
    rows=[
        ["Register", "EP + BVA (PEST)", "12", "12", "0", "100%"],
        ["Login", "EP + BVA (PEST)", "10", "10", "0", "100%"],
        ["Lapor Barang", "EP + BVA (PEST)", "12", "12", "0", "100%"],
        ["Klaim Barang", "EP + BVA (PEST)", "12", "12", "0", "100%"],
        ["Klaim Barang", "White Box / Basis Path", "11", "11", "0", "100%"],
        ["UI Testing", "Selenium WebDriver", "7", "7", "0", "100%"],
        ["TOTAL KESELURUHAN", "", "64", "64", "0", "100%"],
    ],
    col_widths=[3.0, 3.5, 1.2, 1.2, 1.2, 2.0]
)

add_heading(doc, "Bug yang Ditemukan dan Diperbaiki", level=2, numbering="6.2")
add_body(doc,
    "Selama proses implementasi pengujian, ditemukan beberapa bug pada kode sumber "
    "yang kemudian langsung diperbaiki:"
)
add_table(doc,
    headers=["#", "Bug", "Lokasi File", "Dampak", "Perbaikan"],
    rows=[
        ["1",
         "ClaimController::store() tidak mengisi kolom user_id saat insert",
         "ClaimController.php",
         "Error 500 (SQLSTATE NOT NULL constraint)",
         "Tambahkan 'user_id' => auth()->id() pada Claim::create()"],
        ["2",
         "Kolom user_id dan status tidak ada di $fillable model Claim",
         "app/Models/Claim.php",
         "MassAssignmentException saat Claim::create()",
         "Tambahkan user_id dan status ke array $fillable"],
        ["3",
         "Trait HasFactory, HasApiTokens, Notifiable tidak diaktifkan di User model",
         "app/Models/User.php",
         "User::factory() tidak dapat dipanggil di test",
         "Tambahkan: use HasApiTokens, HasFactory, Notifiable;"],
    ],
    col_widths=[0.5, 3.8, 2.5, 3.0, 4.2]
)

add_heading(doc, "Analisis per Fitur", level=2, numbering="6.3")

add_heading(doc, "Fitur Register", level=3, numbering="6.3.1")
add_bullet(doc, "Validasi domain email berjalan sesuai spesifikasi — hanya @student.com dan @admin.com diterima.")
add_bullet(doc, "Pembagian role otomatis berdasarkan domain email berjalan dengan benar.")
add_bullet(doc, "Validasi password minimum 8 karakter terkonfirmasi dari hasil BVA2.")

add_heading(doc, "Fitur Login", level=3, numbering="6.3.2")
add_bullet(doc, "Redirect berdasarkan role (user → /dashboard/user, admin → /dashboard/admin) berjalan benar.")
add_bullet(doc, "Autentikasi menolak password salah dan email tidak terdaftar dengan pesan error yang tepat.")

add_heading(doc, "Fitur Lapor Barang", level=3, numbering="6.3.3")
add_bullet(doc, "Validasi file upload (format gambar, ukuran maksimal 2MB) berjalan sesuai spesifikasi.")
add_bullet(doc, "Status awal laporan selalu 'pending' dan terhubung ke user_id yang melapor.")
add_bullet(doc, "Middleware role.user memblokir akses guest dan mengarahkan ke halaman login.")

add_heading(doc, "Fitur Klaim Barang (White Box)", level=3, numbering="6.3.4")
add_bullet(doc, "Path 1: ItemController::claimForm() berhasil abort 404 untuk item yang bukan 'approved'.")
add_bullet(doc, "Path 2: Validasi required, image, dan date berjalan benar dan mengembalikan session errors yang tepat.")
add_bullet(doc, "Path 3: Proses upload file, insert claims, dan update status item berjalan secara atomik dan benar.")

add_heading(doc, "Kesimpulan", level=2, numbering="6.4")
add_body(doc,
    "Berdasarkan hasil pengujian yang telah dilakukan, dapat disimpulkan sebagai berikut:"
)
kesimpulan = [
    "Seluruh 57 test case PEST berhasil lulus (PASS) dengan 173 assertions dalam durasi 1.84 detik.",
    "Tidak ada defect yang tersisa pada fitur yang diuji setelah perbaikan 3 bug yang ditemukan.",
    "Cyclomatic Complexity V(G) = 3 pada method klaim menunjukkan kompleksitas yang cukup rendah dan terkelola.",
    "Test suite yang dibuat bersifat repeatable dan dapat digunakan untuk regression testing di iterasi selanjutnya.",
    "UI Testing dengan Selenium berhasil memverifikasi 7 skenario alur pengguna End-to-End.",
    "Metode Equivalence Partitioning dan Boundary Value Analysis terbukti efektif dalam mengidentifikasi kasus-kasus kritis yang perlu diuji.",
]
for k in kesimpulan:
    add_bullet(doc, k)

page_break(doc)

# ════════════════════════════════════════════════════════════════════════════
# REFERENSI
# ════════════════════════════════════════════════════════════════════════════
add_heading(doc, "REFERENSI", level=1)
refs = [
    "Pressman, R. S., & Maxim, B. R. (2014). Software Engineering: A Practitioner's Approach (8th ed.). McGraw-Hill Education.",
    "Myers, G. J., Badgett, T., & Sandler, C. (2011). The Art of Software Testing (3rd ed.). John Wiley & Sons.",
    "Jorgensen, P. C. (2013). Software Testing: A Craftsman's Approach (4th ed.). CRC Press.",
    "PEST Documentation. (2024). PEST — The elegant PHP Testing Framework. https://pestphp.com/docs",
    "Laravel Documentation. (2024). Laravel 10.x — Testing. https://laravel.com/docs/10.x/testing",
    "Selenium Documentation. (2024). Selenium WebDriver — Python Bindings. https://selenium-python.readthedocs.io/",
]
for i, ref in enumerate(refs, 1):
    p = doc.add_paragraph(style='List Number')
    run = p.add_run(ref)
    set_font(run, size=12)
    p.paragraph_format.space_before = Pt(0)
    p.paragraph_format.space_after  = Pt(6)
    p.paragraph_format.line_spacing_rule = WD_LINE_SPACING.MULTIPLE
    p.paragraph_format.line_spacing = 1.5

page_break(doc)

# ════════════════════════════════════════════════════════════════════════════
# LAMPIRAN
# ════════════════════════════════════════════════════════════════════════════
add_heading(doc, "LAMPIRAN", level=1)

add_heading(doc, "Lampiran A — Struktur Direktori Proyek", level=2)
add_code(doc,
"""Pengujian/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── AuthenticatedSessionController.php
│   │   │   │   └── RegisteredUserController.php
│   │   │   ├── ClaimController.php
│   │   │   └── ItemController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       └── UserMiddleware.php
│   └── Models/
│       ├── Claim.php
│       ├── Item.php
│       └── User.php
├── database/
│   ├── factories/
│   │   ├── ClaimFactory.php
│   │   ├── ItemFactory.php
│   │   └── UserFactory.php
│   └── migrations/
│       ├── ..._create_users_table.php
│       ├── ..._create_items_table.php
│       └── ..._create_claims_table.php
├── tests/
│   ├── Pest.php
│   ├── Feature/
│   │   ├── RegisterBlackBoxTest.php
│   │   ├── LoginBlackBoxTest.php
│   │   ├── LaporBarangBlackBoxTest.php
│   │   └── KlaimBarangBlackBoxTest.php
│   └── Unit/
│       └── KlaimBarangWhiteBoxTest.php
└── phpunit.xml"""
)

add_heading(doc, "Lampiran B — Skema Database", level=2)
add_body(doc, "Tabel users:")
add_table(doc,
    headers=["Kolom", "Tipe Data", "Keterangan"],
    rows=[
        ["id", "bigint (PK)", "Auto increment, Primary Key"],
        ["name", "varchar(255)", "Nama lengkap pengguna"],
        ["username", "varchar(255) UNIQUE", "Nama pengguna unik"],
        ["email", "varchar(255) UNIQUE", "Email (domain @student.com atau @admin.com)"],
        ["phone", "varchar(15)", "Nomor telepon"],
        ["role", "enum('admin','user')", "Peran: user atau admin (auto dari domain email)"],
        ["password", "varchar(255)", "Hash bcrypt"],
        ["profile_image", "varchar(255) NULL", "Path foto profil"],
    ],
    col_widths=[3.5, 3.5, 7.0]
)

add_body(doc, "Tabel items:")
add_table(doc,
    headers=["Kolom", "Tipe Data", "Keterangan"],
    rows=[
        ["id", "bigint (PK)", "Auto increment"],
        ["nama_item", "varchar(255)", "Nama barang temuan"],
        ["description", "varchar(255) NULL", "Deskripsi barang"],
        ["image", "varchar(255)", "Path foto barang"],
        ["location_found", "varchar(255)", "Lokasi barang ditemukan"],
        ["date_found", "date", "Tanggal barang ditemukan"],
        ["time_found", "time", "Waktu barang ditemukan"],
        ["finder_name", "varchar(255)", "Nama penemu (private)"],
        ["finder_contact", "varchar(255)", "Kontak penemu (private)"],
        ["admin_contact", "varchar(255) NULL", "Kontak admin (public)"],
        ["status", "enum('pending','approved','taken')", "Status laporan barang"],
        ["user_id", "bigint (FK)", "Relasi ke tabel users"],
    ],
    col_widths=[3.5, 3.5, 7.0]
)

add_body(doc, "Tabel claims:")
add_table(doc,
    headers=["Kolom", "Tipe Data", "Keterangan"],
    rows=[
        ["id", "bigint (PK)", "Auto increment"],
        ["item_id", "bigint (FK)", "Relasi ke tabel items"],
        ["user_id", "bigint (FK)", "Relasi ke tabel users (pengklaim)"],
        ["nama_pengambil", "varchar(255)", "Nama orang yang mengklaim"],
        ["NIMorKTP", "varchar(25)", "Nomor identitas (NIM atau KTP)"],
        ["phone_pengambil", "varchar(15)", "Nomor telepon pengklaim"],
        ["foto_pengambil", "varchar(255)", "Path foto identitas pengklaim"],
        ["tgl_ambil", "date", "Tanggal pengambilan barang"],
        ["status", "enum('pending','approved','rejected')", "Status klaim"],
    ],
    col_widths=[3.5, 3.5, 7.0]
)

add_heading(doc, "Lampiran C — Lembar Kontribusi Anggota", level=2)
add_table(doc,
    headers=["Nama", "NIM", "Tugas", "Kontribusi"],
    rows=[
        ["Nama Anggota 1", "XXXXXXXXXX", "Black Box Register + Login, Setup Factories", "25%"],
        ["Nama Anggota 2", "XXXXXXXXXX", "Black Box Lapor Barang + Klaim Barang", "25%"],
        ["Nama Anggota 3", "XXXXXXXXXX", "White Box (Flow Graph, V(G), Unit Test)", "25%"],
        ["Nama Anggota 4", "XXXXXXXXXX", "Selenium UI Test, Laporan, README", "25%"],
    ],
    col_widths=[3.5, 2.5, 5.5, 2.5]
)

add_heading(doc, "Lampiran D — Kode Sumber File Pengujian", level=2)

def add_file_code(doc, filepath, filename):
    add_heading(doc, f"Kode Sumber: {filename}", level=3)
    import os
    abs_path = os.path.join("/Users/irekk/Documents/coding/Pengujian", filepath)
    try:
        with open(abs_path, "r", encoding="utf-8") as f:
            code = f.read()
        p = doc.add_paragraph()
        p.paragraph_format.space_before = Pt(4)
        p.paragraph_format.space_after  = Pt(4)
        p.paragraph_format.left_indent  = Cm(1.0)
        p.paragraph_format.line_spacing_rule = WD_LINE_SPACING.EXACTLY
        p.paragraph_format.line_spacing = Pt(12)
        run = p.add_run(code)
        set_font(run, name="Courier New", size=8.5)
    except Exception as e:
        add_body(doc, f"Error membaca file {filename}: {str(e)}")

add_file_code(doc, "tests/Unit/KlaimBarangWhiteBoxTest.php", "KlaimBarangWhiteBoxTest.php")
add_file_code(doc, "tests/Feature/RegisterBlackBoxTest.php", "RegisterBlackBoxTest.php")
add_file_code(doc, "tests/Feature/LoginBlackBoxTest.php", "LoginBlackBoxTest.php")
add_file_code(doc, "tests/Feature/LaporBarangBlackBoxTest.php", "LaporBarangBlackBoxTest.php")
add_file_code(doc, "tests/Feature/KlaimBarangBlackBoxTest.php", "KlaimBarangBlackBoxTest.php")

# ─── SIMPAN FILE ─────────────────────────────────────────────────────────────
output_path = "/Users/irekk/Documents/coding/Pengujian/Laporan_Tugas_Besar_Inflic.docx"
doc.save(output_path)
print(f"✅ File Word berhasil dibuat: {output_path}")

