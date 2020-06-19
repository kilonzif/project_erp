<?php
/**
 * Created by PhpStorm.
 * User: Sowee - Makedu
 * Date: 10/1/2018
 * Time: 7:13 PM
 */

namespace App\Classes;

use App\ReportingPeriod;
use App\SystemOption;
use Illuminate\Support\Facades\Auth;

class CommonFunctions {

    public function getStatusLabelA($code = null) {
        $labels = [
            1 => 'Core',
            2 => 'Unit if Measure',
            3 => 'Specifics',
            4 => 'Baseline',
            5 => 'Cumulative Target Value',
            6 => 'Results as of ',

        ];
        if ($code == null) {
            return $labels;
        } else {
            return $labels[$code];
        }
    }

    public function getStatusLabelB($code = null) {
        $labels = [
            1 => 'Country',
            2 => 'Ace',
            3 => 'Letter Dated',
            4 => 'Date Dispatched',
            5 => 'Payment In Respect Of',
            6 => 'Amount Due',
            7 => 'Total',

        ];
        if ($code == null) {
            return $labels;
        } else {
            return $labels[$code];
        }
    }

    public function getStatusLabel($code = null) {
        $labels = [
            1 => 'Send M&E',
            2 => 'Review and reply',
            3 => 'Share comments with ACE',
            4 => 'Send revised data',
            5 => 'Send out survey',
            6 => 'Survey closed',
            7 => 'Initial results reported',
            8 => 'Review results',
            9 => 'Send revised results',
            10 => 'Share results with ACE',
            11 => 'Requested verification',
            12 => 'Verification visit',
            13 => 'Visit report',
            14 => 'Shortcomings rectified',
            15 => 'Feedback on results',
            16 => 'Final verification letter',
            17 => 'Disbursement authorization',
            18 => 'Withdrawal Application Submitted',
            19 => 'Funds disbursed',
            99 => 'Uncompleted Report',
            100 => 'Under Review',
            101 => 'Verified',
        ];
        if ($code == null) {
            return $labels;
        } else {
            return $labels[$code];
        }
    }

    public function getDLR_indicator($type_id) {
        $type = [
            'students' => [1],
            'quality' => [3],
            'revenue' => [4],
            'infrastructure' => [5],
//            4=>[1],
        ];
        return $type[$type_id][0];
    }

    public function getStudentProcess() {
        return [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 16, 17, 18, 19];
    }

    public function getRevenueProcess() {
        return [1, 101, 16, 17, 18, 19];
    }

    public function getQualityProcess() {
        return [8, 16, 17, 18, 19];
    }

    public function getInfrastructureProcess() {
        return [11, 12, 13, 14, 16, 17, 18, 19];
    }

    public function getStatusLabelRep($code = null) {

        $labels = [
            1 => 'ACE',
            2 => 'Technopolis',
            3 => 'AAU',
            4 => 'ACE',
            5 => 'Technopolis',
            6 => 'Technopolis',
            7 => 'Technopolis',
            8 => 'AAU/WB',
            9 => 'Technopolis',
            10 => 'AAU',
            11 => 'ACE',
            12 => 'AAU',
            13 => 'AAU',
            14 => 'ACE',
            15 => 'ACE',
            16 => 'AAU',
            17 => 'World Bank',
            18 => 'AAU/Gov',
            19 => 'World Bank',
            101 => 'AAU',
        ];
        if ($code == null) {
            return $labels;
        } else {
            return $labels[$code];
        }
    }
    public function getStatusResponsibility($code = null) {
        $responsibility = [
            1 => 'Ace',
            2 => 'AAU',
            3 => 'Technopolis',
            4 => 'WB/AAU',
            5 => 'World Bank',
            6 => 'ACE/Govt',
            99 => 'Anonymous',
            100 => 'N/A',
        ];

        if ($code == null) {
            return $responsibility;
        } else {
            return $responsibility[$code];
        }
    }

    public function isSubmissionOpen() {
        $opened = ReportingPeriod::where('active_period','=',1)->count();
//		$opened = SystemOption::where('option_name', '=', 'app_deadline')
//			->where('status', '=', 1)
//			->count();
        if ($opened > 0 or Auth::user()->ability(['webmaster', 'super-admin'], ['close-submission'])) {
            return true;
        } else {
            return false;
        }
    }

    public function reportStatusTag($status) {
        switch ($status) {
            case 1:
                $tag = '<div class="badge badge-glow badge-pill badge-primary" style="margin-top: 10px;">Submitted</div>';
                break;
            case 100:
                $tag = '<div class="badge badge-glow badge-pill badge-info" style="margin-top: 10px;">Under Review</div>';
                break;
            case 101:
                $tag = '<div class="badge badge-glow badge-pill badge-success" style="margin-top: 10px;">Report Verified</div>';
                break;
            default:
                $tag = '<div class="badge badge-glow badge-pill badge-danger" style="margin-top: 10px;">Uncompleted</div>';
        }
        return $tag;
    }

    public function currentUrl() {
//        $pageURL = (isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
        //        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        //        if( ! $trim_query_string ) {
        //            return $pageURL;
        //        } else {
        //            $url = explode( '?', $pageURL );
        //            return $url[0];
        //        }
        $data = basename($_SERVER['REQUEST_URI']);
        if (($pos = strpos($data, "_")) !== FALSE) {
            $theQuery = substr($data, $pos + 1);
            return $theQuery;
        }
    }

    public function getRequirements( $code=null)
    {
        $requirement = ['THE OFFICIAL DESIGNATION OF CORE TEAM MEMBERS','IMPLEMENTATION PLAN','PROCEDURES MANUAL',
            'PROJECT MANAGEMENT CERTIFICATION', 'STUDENT HANDBOOK (INCLUDES SCHOLARSHIP AND ANTI-SEXUAL HARASSMENT POLICIES)',
            'PROJECT WEBSITE','SECTORAL ADVISORY BOARD'];

        if ($code == null) {
            return $requirement;
        } else {
            return $requirement[$code];
        }

    }

    public function getContactTitles( $code=null)
    {
        $titles =['Country TTL','Vice Chancelor','Center Leader','Procurement Officer','Finance Officer','MEL Officer','PSC Member'];

        if ($code == null) {
            return $titles;
        } else {
            return $titles[$code];
        }

    }






    public function getRequirementLabels( $code=null)
    {
        $requirement_labels = [

            'THE OFFICIAL DESIGNATION OF CORE TEAM MEMBERS' => [
                'submission_date' => [
                    'show'  =>  true,
                    'required'  =>  true,
                ],
                'file1' => TRUE,
                'file2' => FALSE,
                'comments' => FALSE,
                'url' => FALSE
            ],
            'IMPLEMENTATION PLAN'=>[
                'submission_date' => [
                    'show'  =>  true,
                    'required'  =>  true,
                ],
                'file1' => TRUE,
                'file2' => FALSE,
                'comments' => FALSE,
                'url' => FALSE
            ],
            'PROCEDURES MANUAL'=>[
                'submission_date' => [
                    'show'  =>  true,
                    'required'  =>  true,
                ],
                'file1' => TRUE,
                'file2' => TRUE,
                'comments' => FALSE,
                'url' => FALSE
            ],
            'PROJECT MANAGEMENT CERTIFICATION'=>[
                'submission_date' => [
                    'show'  =>  true,
                    'required'  =>  true,
                ],
                'file1' => TRUE,
                'file2' => FALSE,
                'comments' => FALSE,
                'url' => FALSE
            ],
            'STUDENT HANDBOOK (INCLUDES SCHOLARSHIP AND ANTI-SEXUAL HARASSMENT POLICIES)'=>[
                'submission_date' => [
                    'show'  =>  true,
                    'required'  =>  true,
                ],
                'file1' => TRUE,
                'file2' => FALSE,
                'comments' => FALSE,
                'url' => TRUE
            ],
            'PROJECT WEBSITE'=>[
                'submission_date' => [
                    'show'  =>  true,
                    'required'  =>  true,
                ],
                'file1' => FALSE,
                'file2' => FALSE,
                'comments' => TRUE,
                'url' => TRUE
            ],
//            'SECTORAL ADVISORY BOARD'=>[
//                'submission_date' => [
//                    'show'  =>  true,
//                    'required'  =>  true,
//                ],
//                'file1' => TRUE,
//                'file2' => FALSE,
//                'comments' => FALSE,
//                'url' => FALSE
//            ]
        ];

        if ($code == null) {
            return $requirement_labels;
        } else {
            return $requirement_labels[$code];
        }
    }


    /**
     * @param string $language
     * @return mixed
     */
    public function webFormLang($language)
    {
        switch ($language) {
            case 'english':
                $lang = $this->english_language();
                break;
            case 'french':
                $lang = $this->french_language();
                break;
            default:
                $lang = $this->english_language();
        }
        return $lang;
    }

    /**
     * @return array
     */
    public function english_language()
    {
        return [
            'Edit'                                          =>  'Edit',
            'Save'                                          =>  'Save',
            'Update'                                        =>  'Update',
            'File'                                          =>  'File',
            'Edit Report'                                   =>  'Edit Report',
            'View Data'                                     =>  'View Data',
            'Add Record'                                    =>  'Add Record',
            'Download uploaded data'                        =>  'Download uploaded data',
            'Download'                                      =>  'Download',
            'Action'                                        =>  'Action',
            'Scroll down this page to submit the report'    =>  'Scroll down this page to submit the report',
            'Unit of Measure'                               =>  'Unit of Measure',
            'Upload DLR'                                    =>  'Upload DLR',
            'Upload Indicator'                              =>  'Upload Indicator',
            'Browse File'                                   =>  'Browse File',
            'Challenges faced'                              =>  'Challenges faced',
            'Additional Comments'                           =>  'Additional Comments',
            'Upload DLR data in Bulk'                       =>  'Upload DLR data in Bulk',
            'Period covered by IFR'                         =>  'Period covered by IFR',
            'Period covered by EFA'                         =>  'Period covered by EFA',
            'File Upload'                                   =>  'File upload',
            'Date of Submission'                            =>  'Date of Submission',
            'Submission Date'                               =>  'Submission Date',
            'Amount (USD)'                                  =>  'Amount (USD)',
            'Purpose of Funds'                              =>  'Purpose of Funds',
            'Region'                                        =>  'Region',
            'Account Details'                               =>  'Account Details',
            'Date of Receipt'                               =>  'Date of Receipt',
            'Source'                                        =>  'Source',
            'Select One'                                    =>  'Select One',
            'Original Amount Currency'                      =>  'Original Amount Currency',
            'Original Amount'                               =>  'Original Amount',
            'National'                                      =>  'National',
            'International'                                 =>  'International',
            'Regional'                                      =>  'Regional',
            'Guideline File'                                =>   'Guideline File',
            'Report File'                                   =>   'Report File',
            'Audited Account File'                          =>   'Audited Account File',
            'Members File'                                  =>   'Members File',
            'Approved Procurement'                          =>   'Approved Procurement',
            'ACE Procurement Officer'                       =>   'ACE Procurement Officer',
            'Procurement Progress Report'                   =>   'Procurement Progress Report',
            'Contracts Signed'                              =>   'Contracts Signed',
            'Document'                                      =>   'Document',
            'Document 1 Description'                        =>   'Document 1 Description',
            'Document 2 Description'                        =>   'Document 2 Description',
            'Document 3 Description'                        =>   'Document 3 Description',
            'Financial Report URL'                          =>   'Financial Report URL',
            'Budget Report URL'                             =>   'Budget Report URL',
            'Work Plan URL'                                 =>   'Work Plan URL',
            'Other Files URL'                               =>   'Other Files URL',
            'Personnel File'                                =>   'Personnel File',
            'Vacancy URL'                                   =>   'Vacancy URL',
            'Report Scores File'                            =>   'Report Scores File',
            'Participated Paset'                            => 'Participated Paset',
            'Participated Initiatives'                      => 'Participated Initiatives',
            'Benchmarking Year'                             => 'Benchmarking Year',
            'Self Assessment File'                          => 'Self Assessment File',
            'Intervention Plan File'                        => 'Intervention Plan File',
            'Connectivity File'                             => 'Connectivity File',
            'Infrastructure Upgrade File'                   => 'Infrastructure Upgrade File',
            'Satisfactory Survey File'                      => 'Satisfactory Survey File',
            'Milestone Targets'                             => 'Milestone Targets',
            'Milestone'                                     => 'Milestone',
            'Milestones'                                    => 'Milestones',
            'Submit for Verification'                       => 'Submit for Verification',
            'Document Proof'                                => 'Document Proof',
            'ULR Proof'                                     => 'ULR Proof',
            'Provide Documents'                             => 'Provide Documents',
            'I have achieved all the Milestone Targets above and requesting for verification. 
            No further changes shall be done'
            => 'I have achieved all the Milestone Targets above and requesting for verification. 
            No further changes should be done',
            'Saved Records'                                 => 'Saved Records',
            'Type'                                          => 'Type',
            'Level'                                         => 'Level',
            'Agency'                                        => 'Agency',
            'Reference'                                     => 'Reference',
            'Contact Name'                                  => 'Contact Name',
            'Email'                                         => 'Email',
            'Phone Number'                                  => 'Phone Number',
            'Accreditation Date'                            => 'Accreditation Date',
            'Accreditation Expiry Date'                     => 'Accreditation Expiry Date',
        ];
    }

    /**
     * @return array
     */
    public function french_language()
    {
        return [
            'Edit'                                          =>  'Éditer',
            'Save'                                          =>  'Sauver',
            'Update'                                        =>  'Mise à jour',
            'File'                                          =>  'Fichier',
            'Edit Report'                                   =>  'Modifier le rapport',
            'View Data'                                     =>  'Afficher les données',
            'Add Record'                                    =>  'Ajouter un enregistrement',
            'Download uploaded data'                        =>  'Télécharger les données uploadées',
            'Download'                                      =>  'Télécharger',
            'Action'                                        =>  'Action',
            'Scroll down this page to submit the report'    =>  'Faites défiler cette page pour soumettre le rapport',
            'Unit of Measure'                               =>  'Unité de Mésure',
            'Upload DLR'                                    =>  'Télécharger DLR',
            'Upload Indicator'                              =>  'Téléchargement d’indicateur',
            'Browse File'                                   =>  'Parcourir le fichier',
            'Challenges faced'                              =>  'Défis rencontrés',
            'Additional Comments'                           =>  'Commentaires supplémentaires',
            'Upload DLR data in Bulk'                       =>  'Télécharger des données DLR en masse',
            'Period covered by IFR'                         =>  'Période couverte par IFR',
            'Period covered by EFA'                         =>  'Période couverte par EFA',
            'File Upload'                                   =>  'Téléchargement de fichiers',
            'Date of Submission'                            =>  'Date de Soumission',
            'Submission Date'                               =>  'Date de Soumission',
            'Amount (USD)'                                  =>  'Montant (USD)',
            'Purpose of Funds'                              =>  'Objet des fonds',
            'Account Details'                               =>  'Détails du compte',
            'Region'                                        =>  'Région',
            'Date of Receipt'                               =>  'Date de réception',
            'Source'                                        =>  'La source',
            'Select One'                                    =>  'Sélectionnez un',
            'Original Amount Currency'                      =>  'Devise du montant d\'origine',
            'Original Amount'                               =>  'Montant original',
            'National'                                      =>  'National',
            'International'                                 =>  'International',
            'Regional'                                      =>  'Régional',
            'Guideline File'                                =>  'Fichier des lignes directrices',
            'Report File'                                   =>   'Fichier de rapport',
            'Audited Account File'                          =>   'Fichier de compte vérifié',
            'Members File'                                  =>   'Fichier des membres',
            'Approved Procurement'                          =>   'Approvisionnement approuvé',
            'ACE Procurement Officer'                       =>   'Responsable des achats ACE',
            'Procurement Progress Report'                   =>   'Rapport d\'étape sur l\'approvisionnement',
            'Contracts Signed'                              =>   'Contrats signés',
            'Document'                                      =>   'Document',
            'Document 1 Description'                        =>   'Description du document 1',
            'Document 2 Description'                        =>   'Description du document 2',
            'Document 3 Description'                        =>   'Description du document 3',
            'Financial Report URL'                          =>   'URL du rapport financier',
            'Budget Report URL'                             =>   'URL du rapport budgétaire',
            'Work Plan URL'                                 =>   'URL du plan de travail',
            'Other Files URL'                               =>   'URL d\'autres fichiers',
            'Personnel File'                                => 'Dossier personnel',
            'Vacancy URL'                                   => 'URL de poste vacant',
            'Report Scores File'                            => 'Fichier de résultats de rapport',
            'Participated Paset'                            => 'Paset participé',
            'Participated Initiatives'                      => 'Initiatives participantes',
            'Benchmarking Year'                             => 'Année de référence',
            'Self Assessment File'                          => 'Fichier d\'auto-évaluation',
            'Intervention Plan File'                        => 'Fichier de plan d\'intervention',
            'Connectivity File'                             => 'Fichier de connectivité',
            'Infrastructure Upgrade File'                   => 'Fichier de mise à niveau de l\'infrastructure',
            'Satisfactory Survey File'                      => 'Fichier d\'enquête satisfaisant',
            'Milestone Targets'                             => 'Objectifs clés',
            'Milestone'                                     => 'Étape importante',
            'Milestones'                                    => 'Jalons',
            'Document Proof'                                => 'Preuve de document',
            'URL Proof'                                     => 'Preuve ULR',
            'Provide Documents'                             => 'Fournir des documents',
            'I have achieved all the Milestone Targets above and requesting for verification. 
            No further changes shall be done.'
            => 'J\'ai atteint tous les objectifs clés ci-dessus et j\'ai demandé une vérification.
            Aucune autre modification ne doit être effectuée',
            'Submit for Verification'                       => 'Soumettre pour vérification',
            'Saved Records'                                 => 'Enregistrements enregistréss',
            'Accreditation Type'                            => 'Type d\'accréditation',
            'Accreditation Level'                           => 'Niveau d\'accréditation',
            'Accreditation Agency'                          => 'Agence d\'accréditation',
            'Accreditation Reference'                       => 'Référence d\'accréditation',
            'Agency Contact Name'                           => 'Nom du contact de l\'agence',
            'Agency Contact Email'                          => 'Email du contact de l\'agence',
            'Agency Contact Phone Number'                   => 'Numéro de téléphone de l\'agence',
            'Date of Accreditation'                         => 'Date d\'accréditation',
            'Expiry Date of Accreditation'                   => 'Date d\'expiration de l\'accréditation',
            'Newly Accredited'                              => 'Nouvellement accrédité',
            'Newly Accredited Programme?'                   => 'Programme nouvellement accrédité?',
            'Program Title'                                 => 'Titre du programme',
            'Are you sure you want to delete this record?'  => 'Voulez-vous vraiment supprimer cet enregistrement?',
            'Delete Record'                                 => 'Supprimer l\'enregistrement',
        ];


    }
}