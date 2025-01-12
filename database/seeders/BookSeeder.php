<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
    public function run(): void
    {
        
        $books = [
            [
                'id' => (string) \Str::uuid(),
                'title' => 'Fundamentos de matemáticas para Bachillerato',
                'description' => 'El Instituto de Ciencias Matemáticas de la ESPOL, responsable de la enseñanza de una de las ciencias básicas del conocimiento humano, tiene como parte de su Misión: "Definir lineamientos y elevar el nivel de la Educación Matemática en el país"; consecuente con este principio, ha creído conveniente diseñar y desarrollar el presente texto, como un apoyo dirigido a los estudiantes del Bachillerato. Este libro revisa temas básicos de nivel secundario y, por su amplitud y profundidad, constituye una guía necesaria dentro de su proceso de aprendizaje. El presente texto ha sido estructurado de tal manera que sea de fácil lectura y comprensión, asequible para estudiantes de colegios que requieran fortalecer su formación matemática, preparándose así para enfrentar los retos que la vida universitaria les depare.',
                'image' => 'b76c99c9-84f6-4167-977a-bef0fb772290_20250106_175032.png',
                'autor' => 'ESPOL',
                'emission' => '2007-01-01',
                'units' => 8,
                'status' => true,
                'categories' => ['CATE001', 'CATE002'], 
            ],
            [
                'id' => (string) \Str::uuid(),
                'title' => 'Clean code',
                'description' => 'Robert Cecil Martin es un ingeniero de software y autor estadounidense, reconocido por desarrollar varios principios de diseño de software y ser uno de los coautores del Manifiesto Ágil. Martin es autor de varios artículos y libros. Fue el editor de la revista C++ Report y primer director de la Agile Alliance.',
                'image' => '2937d7b7-9a0b-4009-a652-7f7709f20b5a_20250106_175137.jpg',
                'autor' => 'Robert Cecil Martin',
                'emission' => '2008-08-01',
                'units' => 8,
                'status' => true,
                'categories' => ['CATE003', 'CATE006', 'CATE007'],
            ],
            [
                'id' => (string) \Str::uuid(),
                'title' => 'JAVA 9',
                'description' => 'El diseño de Java, su robustez, el respaldo de la industria y su fácil portabilidad han hecho de Java uno de los lenguajes con un mayor crecimiento y amplitud de uso en distintas áreas de la industria de la informática. Su gran impacto en el desarrollo web, su protagonismo en el ámbito de las aplicaciones para dispositivos móviles, e incluso su sencillez y dinamismo para crear aplicaciones de escritorio, hacen de Java la plataforma de desarrollo número uno del mundo.',
                'image' => 'ef54a3b0-a7b3-489f-abb9-590deb7e2de2_20250106_175318.jpg',
                'autor' => 'Herbert Schildt',
                'emission' => '2010-05-01',
                'units' => 8,
                'status' => true,
                'categories' => ['CATE003', 'CATE006', 'CATE007'],
            ],
            [
                'id' => (string) \Str::uuid(),
                'title' => 'Tecnologías de la Información Vol. 4',
                'description' => 'Las TI son las Tecnologías de la Información, un conjunto de herramientas, recursos y estrategias que se utilizan para crear, gestionar, mantener y mejorar sistemas informáticos, redes virtuales y plataformas digitales.',
                'image' => '2937d7b7-9a0b-4009-a652-7f7709f20b5a_20250106_175137.jpg',
                'autor' => 'Linda Green',
                'emission' => '2020-05-01',
                'units' => 8,
                'status' => true,
                'categories' => ['CATE003', 'CATE006', 'CATE007'], 
            ],
            [
                'id' => (string) \Str::uuid(),
                'title' => 'Tecnologías de la Información Vol. 5',
                'description' => 'Las TI son las Tecnologías de la Información, un conjunto de herramientas, recursos y estrategias que se utilizan para crear, gestionar, mantener y mejorar sistemas informáticos, redes virtuales y plataformas digitales.',
                'image' => '2937d7b7-9a0b-4009-a652-7f7709f20b5a_20250106_175137.jpg',
                'autor' => 'Linda Green',
                'emission' => '2020-05-01',
                'units' => 8,
                'status' => true,
                'categories' => ['CATE007'],
            ],
            [
                'id' => (string) \Str::uuid(),
                'title' => 'Tecnologías de la Información Vol. 6',
                'description' => 'Las TI son las Tecnologías de la Información, un conjunto de herramientas, recursos y estrategias que se utilizan para crear, gestionar, mantener y mejorar sistemas informáticos, redes virtuales y plataformas digitales.',
                'image' => 'ef54a3b0-a7b3-489f-abb9-590deb7e2de2_20250106_175318.jpg',
                'autor' => 'Linda Green',
                'emission' => '2020-05-01',
                'units' => 8,
                'status' => true,
                'categories' => ['CATE003'],
            ],
            [
                'id' => (string) \Str::uuid(),
                'title' => 'Tecnologías de la Información Vol. 7',
                'description' => 'Las TI son las Tecnologías de la Información, un conjunto de herramientas, recursos y estrategias que se utilizan para crear, gestionar, mantener y mejorar sistemas informáticos, redes virtuales y plataformas digitales.',
                'image' => '53336135-f0fe-4fd2-9fb8-7730a5900a45_20241219_215014_default.jpg',
                'autor' => 'Linda Green',
                'emission' => '2020-05-01',
                'units' => 8,
                'status' => true,
                'categories' => ['CATE006'], 
            ],
            [
                'id' => (string) \Str::uuid(),
                'title' => 'Tecnologías de la Información Vol. 8',
                'description' => 'Las TI son las Tecnologías de la Información, un conjunto de herramientas, recursos y estrategias que se utilizan para crear, gestionar, mantener y mejorar sistemas informáticos, redes virtuales y plataformas digitales.',
                'image' => 'ef54a3b0-a7b3-489f-abb9-590deb7e2de2_20250106_175318.jpg',
                'autor' => 'Linda Green',
                'emission' => '2020-05-01',
                'units' => 8,
                'status' => true,
                'categories' => ['CATE002'],
            ],
            [
                'id' => (string) \Str::uuid(),
                'title' => 'Tecnologías de la Información Vol. 9',
                'description' => 'Las TI son las Tecnologías de la Información, un conjunto de herramientas, recursos y estrategias que se utilizan para crear, gestionar, mantener y mejorar sistemas informáticos, redes virtuales y plataformas digitales.',
                'image' => 'ef54a3b0-a7b3-489f-abb9-590deb7e2de2_20250106_175318.jpg',
                'autor' => 'Linda Green',
                'emission' => '2020-05-01',
                'units' => 8,
                'status' => true,
                'categories' => ['CATE001', 'CATE007'],
            ],
            [
                'id' => (string) \Str::uuid(),
                'title' => 'Tecnologías de la Información Vol. 10',
                'description' => 'Las TI son las Tecnologías de la Información, un conjunto de herramientas, recursos y estrategias que se utilizan para crear, gestionar, mantener y mejorar sistemas informáticos, redes virtuales y plataformas digitales.',
                'image' => 'ef54a3b0-a7b3-489f-abb9-590deb7e2de2_20250106_175318.jpg',
                'autor' => 'Linda Green',
                'emission' => '2020-05-01',
                'units' => 8,
                'status' => true,
                'categories' => ['CATE003', 'CATE006', 'CATE007'],
            ],
            [
                'id' => (string) \Str::uuid(),
                'title' => 'Tecnologías de la Información Vol. 11',
                'description' => 'Las TI son las Tecnologías de la Información, un conjunto de herramientas, recursos y estrategias que se utilizan para crear, gestionar, mantener y mejorar sistemas informáticos, redes virtuales y plataformas digitales.',
                'image' => 'ef54a3b0-a7b3-489f-abb9-590deb7e2de2_20250106_175318.jpg',
                'autor' => 'Linda Green',
                'emission' => '2020-05-01',
                'units' => 8,
                'status' => true,
                'categories' => ['CATE002', 'CATE001'],
            ],
            [
                'id' => (string) \Str::uuid(),
                'title' => 'Tecnologías de la Información Vol. 12',
                'description' => 'Las TI son las Tecnologías de la Información, un conjunto de herramientas, recursos y estrategias que se utilizan para crear, gestionar, mantener y mejorar sistemas informáticos, redes virtuales y plataformas digitales.',
                'image' => 'ef54a3b0-a7b3-489f-abb9-590deb7e2de2_20250106_175318.jpg',
                'autor' => 'Linda Green',
                'emission' => '2020-05-01',
                'units' => 8,
                'status' => true,
                'categories' => ['CATE002', 'CATE001'],
            ],
        ];



        foreach ($books as $bookData) {
        // Crear el libro
        $book = Book::create([
            'id' => $bookData['id'],
            'title' => $bookData['title'],
            'description' => $bookData['description'],
            'image' => $bookData['image'],
            'autor' => $bookData['autor'],
            'emission' => $bookData['emission'],
            'units' => $bookData['units'],
            'status' => $bookData['status'],
        ]);
            


        
        $categoryIds = Category::whereIn('code', $bookData['categories'])->pluck('id');

        
        $book->categories()->attach($categoryIds);
        }
            



    }
    
}

