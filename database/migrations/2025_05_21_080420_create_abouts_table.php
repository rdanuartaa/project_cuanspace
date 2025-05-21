public function up()
{
    Schema::create('abouts', function (Blueprint $table) {
        $table->id();
        $table->string('judul');
        $table->text('deskripsi');
        $table->string('thumbnail');
        $table->text('visi');
        $table->text('misi');
        $table->timestamps();
    });
}
 