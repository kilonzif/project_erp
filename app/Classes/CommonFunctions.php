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
        $requirement = ['THE OFFICIAL DESIGNATION OF CORE TEAM MEMBERS','IMPLEMENTATION PLAN','PROCUREMENT MANUAL',
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
            'PROCUREMENT MANUAL'=>[
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
            'Action'                                        =>  'Action',
            'Upload DLR'                                    =>  'Upload DLR',
            'Browse File'                                   =>  'Browse File',
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
            'Action'                                        =>  'Action',
            'Upload DLR'                                    =>  'Télécharger DLR',
            'Browse File'                                   =>  'Parcourir le fichier',
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
        ];
    }
}