<?php

declare(strict_types=1);

namespace App\Report;

use App\Entity\JobSource;

readonly class Figure92 extends Figure
{
    protected function getTitle(): string
    {
        return 'Figure 92. The quantity of times of Industries mentioned according to the dataset collected from the Adzuna resource.';
    }

    protected function getSql(): string
    {
        return <<<EOL
SELECT sji.title, count(DISTINCT job_source_id) as job_count
FROM source_jobs_industry sji
         LEFT JOIN source_jobs sj ON sj.id = sji.job_source_id
GROUP BY sji.title, sj.source
order by job_count desc
EOL;
;
    }

    protected static function transformOptions(array $data, string $title): string
    {
        return json_encode([
                'chart'       => [
                    'type' => 'bar',
                ],
                'series'      => [
                    [
                        'data' => array_values($data)
                    ]
                ],
                'plotOptions' => [
                    'bar' => [
                        'horizontal' => true,
                    ]
                ],
                'xaxis'       => [
                    'categories' => array_keys($data)
                ],
                'title'       => [
                    'align'    => 'left',
                    'floating' => false,
                    'text'     => $title,
                ],
            ]
        );
    }

    protected static function transformLabels(array $data): array
    {
        $industries = [
            'vacatures'           => 'HR',
            'IT-Stellen'          => 'IT',
            'Stellen'             => 'IT',
            'QA'                  => 'IT',
            'informática'         => 'IT',
            'ICT'                 => 'IT',
            'Emplois'             => 'HR',
            'Trabajos'            => 'HR',
            'Recruitment'         => 'HR',
            'Vagas'               => 'HR',
            'Empleos'             => 'HR',
            'Vacatures'           => 'HR',
            'Verwaltungsstellen'  => 'Administrative',
            'Admin'               => 'Administrative',
            'Finanzwesen'         => 'Finance',
            'finanse'             => 'Finance',
            'Finan'               => 'Finance',
            'Finanza'             => 'Finance',
            'Reclame'             => 'Marketing',
            'Advertising'         => 'Marketing',
            'Services'            => 'Sales',
            'Customer'            => 'Sales',
            'Creative'            => 'Design',
            'Buchhaltung'         => 'Accounting',
            'Contabilidade'       => 'Accounting',
            'Contabilit'          => 'Accounting',
            'Comptabilit'         => 'Accounting',
            'contabilidad'        => 'Accounting',
            'reklama'             => 'Marketing',
            'marketing'           => 'Marketing',
            'Pubblicit'           => 'Marketing',
            'Werbung'             => 'Marketing',
            'publicidad'          => 'Marketing',
            'Administration'      => 'Administrative',
            'Administratieve'     => 'Administrative',
            'administraci'        => 'Administrative',
            'afgestudeerden'      => 'Education',
            'universitair'        => 'Education',
            'Graduate'            => 'Education',
            'Enseignement'        => 'Education',
            'Ensino'              => 'Education',
            'Logistics'           => 'Logistics',
            'Financi'             => 'Finance',
            'finanzas'            => 'Finance',
            'Gezondheidszorg'     => 'Health',
            'Gesundheitswesen'    => 'Health',
            'Verpleging'          => 'Health',
            'Healthcare'          => 'Health',
            'Hospitalit'          => 'Hospitality',
            'Nursing'             => 'Health',
            'Pflege'              => 'Health',
            'Wetenschap'          => 'Science',
            'Autres'              => 'Other',
            'Outras'              => 'Other',
            'Personal'            => 'Other',
            'Klantenservice'      => 'Other',
            'Kreation'            => 'Other',
            'Kundendienststellen' => 'Other',
            'Qualidade'           => 'Other',
            'Restauration'        => 'Other',
            'Soins'               => 'Other',
            'Controle'            => 'Other',
            'n'                   => 'Other',
            'Cr'                  => 'Other',
            'Cria'                => 'Other',
            'Oil'                 => 'Other',
            'Praca'               => 'Other',
            'Produkcja'           => 'Other',
            'Property'            => 'Other',
            'relaciones'          => 'Other',
            'sant'                => 'Other',
            'Energy'              => 'Other',
            'le'                  => 'Other',
            'tica'                => 'Other',
            'gowo'                => 'Other',
            'ral'                 => 'Other',
            'Bau'                 => 'Other',
            'Propaganda'          => 'Other',
            'Sonstige'            => 'Other',
            'Technikerstellen'    => 'Other',
            'Tecnologia'          => 'Other',
            'G'                   => 'Other',
            'Ing'                 => 'Other',
            'of'                  => 'Other',
            'Ci'                  => 'Other',
            'nierie'              => 'Other',
            'inform'              => 'Other',
            'Handel'              => 'Other',
            'Part'                => 'Other',
            'Publicit'            => 'Other',
            'Warehouse'           => 'Other',
            'Ksi'                 => 'Other',
            'RP'                  => 'Other',
            'Algemeen'            => 'Other',
            'Voyages'             => 'Other',
            'as'                  => 'Other',
            'ation'               => 'Other',
            'blicas'              => 'Other',
            'infirmiers'          => 'Health',
            'Allgemeine'          => 'Other',
            'Industri'            => 'Other',
            'Inna'                => 'Other',
            'lna'                 => 'Other',
            'naukowa'             => 'Other',
            'ncia'                => 'Other',
            'o'                   => 'Other',
            'og'                  => 'Other',
            'p'                   => 'Other',
            'Istruzione'          => 'Other',
            'Formazione'          => 'Other',
            'Gas'                 => 'Other',
            'Gastronomie'         => 'Other',
            'Inform'              => 'Other',
            'Ander'               => 'Other',
            'Trade'               => 'Sales',
            'Vendas'              => 'Sales',
            'Immobilier'          => 'Sales',
            'ventas'              => 'Sales',
            'Vertriebsstellen'    => 'Sales',
            'hosteleria'          => 'Hospitality',
            'leraren'             => 'Education',
            'Nauczanie'           => 'Education',
            'restauraci'          => 'Hospitality',
            'Catering'            => 'Hospitality',
            'Vente'               => 'Sales',
            'Juristische'         => 'Law',
            'Legal'               => 'Law',
            'Bouwkunde'           => 'Other',
            'Lehrberufe'          => 'Other',
            'Personalbeschaffung' => 'HR',
            'Consultancy'         => 'Consulting',
            'Beraterstellen'      => 'Consulting',
            'Advisory positions'  => 'Consulting',
            'Consulenza'          => 'Consulting',
            'Adviseur'            => 'Consulting',
            'Consultants'         => 'Consulting',
            'Fertigung'           => 'Production',
            'Fabrication'         => 'Production',
            'Manufacturing'       => 'Production',
            'PR'                  => 'Public relations',
        ];

        $grouped = [];
        foreach ($data as $title => $jobs) {
            $title = $industries[$title] ?? $title;

            if (empty($title)) {
                continue;
            }

            if (!isset($grouped[$title])) {
                $grouped[$title] = 0;
            }

            $grouped[$title] += $jobs;
        }

        unset($grouped['Other']);

        return array_slice($grouped, 0, 100);;
    }

    public function __invoke(): array
    {
        $data = self::transformLabels($this->execute());
        arsort($data);
        $title = $this->getTitle();

        return [
            'title'   => $title,
            'data'    => $data,
            'options' =>
                self::transformOptions($data, $title)
        ];
    }
}