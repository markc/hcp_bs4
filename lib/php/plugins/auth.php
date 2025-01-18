<?php declare(strict_types=1);
// lib/php/plugins/auth.php 20150101 - 20250116
// Copyright (C) 2015-2025 Mark Constable <markc@renta.net> (AGPL-3.0)

class Plugins_Auth extends Plugin
{
    public const OTP_LENGTH = 10;
    public const REMEMBER_ME_EXP = 604800; // 7 days;

    protected string $tbl = 'accounts';
    protected array $in = [
        'id'        => null,
        'acl'       => null,
        'grp'       => null,
        'login'     => '',
        'webpw'     => '',
        'remember'  => '',
        'otp'       => '',
        'passwd1'   => '',
        'passwd2'   => '',
    ];

    // forgotpw
    public function create(): string
    {
        elog(__METHOD__);

        $u = (string) $this->in['login'];

        if (util::is_post()) {
            if (filter_var($u, FILTER_VALIDATE_EMAIL)) {
                if ($usr = db::read('id,acl', 'login', $u, '', 'one')) {
                    if ((int) $usr['acl'] !== 9) {
                        $newpass = util::genpw(self::OTP_LENGTH);
                        if ($this->mail_forgotpw($u, $newpass, 'From: ' . $this->g->cfg['email'])) {
                            db::update([
                                'otp' => $newpass,
                                'otpttl' => time()
                            ], [['id', '=', $usr['id']]]);
                            util::log('Sent reset password key for "' . $u . '" so please check your mailbox and click on the supplied link.', 'success');
                        } else util::log('Problem sending message to ' . $u, 'danger');
                        util::redirect($this->cfg['self'] . '?o=' . $this->g->in['o'] . '&m=list');
                    } else util::log('Account is disabled, contact your System Administrator');
                } else util::log('User does not exist');
            } else util::log('You must provide a valid email address');
        }
        return $this->t->create(['login' => $u]);
    }

    // login
    public function list(): string
    {
        elog(__METHOD__);

        $u = (string) $this->in['login'];
        $p = (string) $this->in['webpw'];

        // Auto-login if development credentials are configured
        if (!empty($this->g->cfg['email']) && !empty($this->g->cfg['admpw'])) {
            $_SESSION['usr'] = [
                'id' => 0,
                'grp' => 0,
                'acl' => 0,
                'login' => $this->g->cfg['email'],
                'fname' => 'Admin',
                'lname' => 'User'
            ];
            $_SESSION['adm'] = 0;
            util::log($this->g->cfg['email'] . ' auto-logged in', 'success');
            $_SESSION['m'] = 'list';
            util::redirect($this->g->cfg['self']);
        }

        if ($u) {
            if (!empty($this->g->cfg['admpw']) && $u === $this->g->cfg['email'] && $p === $this->g->cfg['admpw']) {
                $_SESSION['usr'] = [
                    'id' => 0,
                    'grp' => 0,
                    'acl' => 0,
                    'login' => $u,
                    'fname' => 'Admin',
                    'lname' => 'User'
                ];
                $_SESSION['adm'] = 0;
                util::log($u . ' is now logged in', 'success');
                $_SESSION['m'] = 'list';
                util::redirect($this->g->cfg['self']);
            }
            if ($usr = db::read('id,grp,acl,login,fname,lname,webpw,cookie', 'login', $u, '', 'one')) {
                extract($usr);
                if ((int) $acl !== 9) {
                    if (password_verify(html_entity_decode($p, ENT_QUOTES, 'UTF-8'), (string) $webpw)) {
                        if ($this->in['remember']) {
                            $uniq = util::random_token(32);
                            db::update(['cookie' => $uniq], [['id', '=', $id]]);
                            util::put_cookie('remember', $uniq, self::REMEMBER_ME_EXP);
                        }
                        $_SESSION['usr'] = $usr;
                        util::log($login.' is now logged in', 'success');
                        if ((int) $acl === 0) $_SESSION['adm'] = $id;
                        $_SESSION['m'] = 'list';
                        util::redirect($this->g->cfg['self']);
                    } else util::log('Invalid Email Or Password');
                } else util::log('Account is disabled, contact your System Administrator');
            } else util::log('Invalid Email Or Password');
        }
        return $this->t->list(['login' => $u]);
    }

    // resetpw
    public function update(): string
    {
        elog(__METHOD__);

        if (!(util::is_usr() || isset($_SESSION['resetpw']))) {
            util::log('Session expired! Please login and try again.');
            util::relist();
        }

        $i = (util::is_usr()) ? (int) $_SESSION['usr']['id'] : (int) $_SESSION['resetpw']['usr']['id'];
        $u = (util::is_usr()) ? (string) $_SESSION['usr']['login'] : (string) $_SESSION['resetpw']['usr']['login'];

        if (util::is_post()) {
            if ($usr = db::read('login,acl,otpttl', 'id', (string) $i, '', 'one')) {
                $p1 = html_entity_decode((string) $this->in['passwd1'], ENT_QUOTES, 'UTF-8');
                $p2 = html_entity_decode((string) $this->in['passwd2'], ENT_QUOTES, 'UTF-8');
                if (util::chkpw($p1, $p2)) {
                    if (util::is_usr() || ($usr['otpttl'] && (((int) $usr['otpttl'] + 3600) > time()))) {
                        if (!is_null($usr['acl'])) {
                            if (db::update([
                                    'webpw'   => password_hash($p1, PASSWORD_DEFAULT),
                                    'otp'     => '',
                                    'otpttl'  => 0,
                                    'updated' => date('Y-m-d H:i:s'),
                                ], [['id', '=', $i]])) {
                                util::log('Password reset for ' . $usr['login'], 'success');
                                if (util::is_usr()) {
                                    util::redirect($this->g->cfg['self']);
                                } else {
                                    unset($_SESSION['resetpw']);
                                    util::relist();
                                }
                            } else util::log('Problem updating database');
                        } else util::log($usr['login'] . ' is not allowed access');
                    } else util::log('Your one time password key has expired');
                }
            } else util::log('User does not exist');
        }
        return $this->t->update(['id' => $i, 'login' => $u]);
    }

    public function delete(): string
    {
        elog(__METHOD__);

        if(util::is_usr()){
            $u = (string) $_SESSION['usr']['login'];
            $id = (int) $_SESSION['usr']['id'];
            if (isset($_SESSION['adm']) && $_SESSION['usr']['id'] === $_SESSION['adm']){
                unset($_SESSION['adm']);
            }
            unset($_SESSION['usr']);
            if(isset($_COOKIE['remember'])){
                db::update(['cookie' => ''], [['id', '=', $id]]);
                $this->setcookie('remember', '', strtotime('-1 hour', 0));
             }
            util::log($u . ' is now logged out', 'success');
        }
        util::redirect($this->g->cfg['self']);
    }

    // Utilities

    public function resetpw(): string
    {
        elog(__METHOD__);

        $otp = html_entity_decode((string) $this->in['otp']);
        if (strlen($otp) === self::OTP_LENGTH) {
            if ($usr = db::read('id,acl,login,otp,otpttl', 'otp', $otp, '', 'one')) {
                extract($usr);
                if ($otpttl && (((int) $otpttl + 3600) > time())) {
                    if ((int) $acl !== 3) { // suspended
                        $_SESSION['resetpw'] = [ 'usr'=> $usr ];
                        return $this->t->update(['id' => $id, 'login' => $login]);
                    } else util::log($login . ' is not allowed access');
                } else util::log('Your one time password key has expired');
            } else util::log('Your one time password key no longer exists');
        } else util::log('Incorrect one time password key');
        util::redirect($this->g->cfg['self']);
    }

    private function mail_forgotpw(string $email, string $newpass, string $headers = ''): bool
    {
        elog(__METHOD__);

        $host = $_SERVER['REQUEST_SCHEME'] . '://'
            . $this->g->cfg['host']
            . $this->g->cfg['self'];
        return mail(
            $email,
            'Reset password for ' . $this->g->cfg['host'],
'Here is your new OTP (one time password) key that is valid for one hour.

Please click on the link below and continue with reseting your password.

If you did not request this action then please ignore this message.

' . $host . '?o=auth&m=resetpw&otp=' . $newpass,
            $headers
        );
    }
}
