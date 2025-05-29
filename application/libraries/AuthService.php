<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * AuthService 클래스
 *
 * 이 클래스는 회원가입, 로그인, 로그아웃과 관련된 인증 기능을 담당합니다.
 * 
 * 주요 기능:
 * - 로그인: 사용자 아이디와 비밀번호 검증 후 세션에 사용자 정보를 저장하고,
 *            성공/실패 메시지를 플래시데이터로 설정합니다.
 * - 로그아웃: 세션에서 사용자 정보를 삭제하고 세션을 완전히 파기합니다.
 *            (현재 sess_destroy() 호출로 인해 플래시 메시지가 로그아웃 시 출력되지 않습니다.)
 * - 회원가입: 중복 아이디 검사 후 신규 사용자 등록, 성공/실패 메시지를 플래시데이터로 설정합니다.
 *
 * 세션 라이브러리를 내부에서 직접 로드하여 플래시메시지 및 세션 관리를 수행합니다.
 * 
 * 주의사항:
 * - 로그아웃 시 sess_destroy() 호출로 인해 플래시데이터가 사라지므로,
 *   로그아웃 메시지를 띄우려면 세션 전체 삭제 방식을 수정할 필요가 있습니다.
 */
class AuthService
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('Users_model');
        $this->CI->load->library('session'); 
    }

    // 로그인 처리
    public function login($user_id, $user_pw)
    {
        $user = $this->CI->Users_model->get_by_user_id($user_id);
        if (!$user) {
            $this->CI->session->set_flashdata('message', '아이디가 없습니다.');
            return ['success' => false];
        }

        if ($user->user_pw !== $user_pw) {
            $this->CI->session->set_flashdata('message', '비밀번호가 일치하지 않습니다.');
            return ['success' => false];
        }

        $this->CI->session->set_userdata('user_id', $user->user_id);
        $this->CI->session->set_flashdata('message', '로그인 성공했습니다.');
        return ['success' => true];
    }

    // 로그아웃 처리
        //로그아웃 시에는 토스트 출력되지 않음. (세션이 삭제되기 때문에)
    public function logout()
    {
        $this->CI->session->unset_userdata('user_id');
        $this->CI->session->sess_destroy();
    }

    // 회원가입 처리
    public function sign_up($user_id, $user_pw)
    {
        $exists = $this->CI->Users_model->get_by_user_id($user_id);
        if ($exists) {
            $this->CI->session->set_flashdata('message', "$user_id 는 이미 존재하는 아이디입니다.");
            return ['success' => false];
        }

        $data = [
            'user_id' => $user_id,
            'user_pw' => $user_pw
        ];

        $result = $this->CI->Users_model->insert($data);

        if ($result) {
            $this->CI->session->set_flashdata('message', '회원가입이 성공했습니다.');
            return ['success' => true];
        } else {
            $this->CI->session->set_flashdata('message', '회원가입이 실패했습니다.');
            return ['success' => false];
        }
    }
}
