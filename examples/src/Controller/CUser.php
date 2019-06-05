<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/5/7
 * Time: 10:48
 */

namespace ESD\Plugins\Tracing\Examples\Controller;

use DI\Annotation\Inject;
use ESD\Examples\Model\User;
use ESD\Examples\Service\UserService;
use ESD\Go\GoController;
use ESD\Plugins\Cache\Annotation\CacheEvict;
use ESD\Plugins\EasyRoute\Annotation\GetMapping;
use ESD\Plugins\EasyRoute\Annotation\PostMapping;
use ESD\Plugins\EasyRoute\Annotation\RequestBody;
use ESD\Plugins\EasyRoute\Annotation\RestController;
use ESD\Plugins\Security\Annotation\PreAuthorize;
use ESD\Plugins\Security\Beans\Principal;

/**
 * @RestController("user")
 * Class CUser
 * @package ESD\Examples\Controller
 */
class CUser extends GoController
{
    /**
     * @Inject()
     * @var UserService
     */
    private $userService;

    /**
     * @GetMapping("login")
     * @return string
     */
    public function login()
    {
        $principal = new Principal();
        $principal->addRole("user");
        $principal->setUsername("user");
        $this->setPrincipal($principal);
        if ($this->session->isAvailable()) {
            return "已登录" . $this->session->getId() . $this->session->getAttribute("test");
        } else {
            $this->session->refresh();
            $this->session->setAttribute("test", "hello");
            return "登录" . $this->session->getId() . $this->session->getAttribute("test");
        }

    }

    /**
     * @GetMapping("logout")
     * @return string
     */
    public function logout()
    {
        $this->session->invalidate();
        return "注销";
    }

    /**
     * @GetMapping("user")
     * @PreAuthorize(value="hasRole('user')")
     * @return User
     * @throws \ESD\Plugins\Mysql\MysqlException
     * @throws \ESD\Plugins\Validate\ValidationException
     * @throws \ESD\Core\Exception
     */
    public function user()
    {
        $id = $this->request->query("id");
        return $this->userService->getUser($id);
    }

    /**
     * @GetMapping("user2")
     * @PreAuthorize(value="hasRole('user')")
     * @return User
     * @throws \ESD\Plugins\Mysql\MysqlException
     * @throws \ESD\Plugins\Validate\ValidationException
     * @throws \ESD\Core\Exception
     */
    public function user2()
    {
        $id = $this->request->query("id");
        return User::select($id);
    }

    /**
     * @GetMapping()
     * @CacheEvict(namespace="user",allEntries=true)
     */
    public function clearCache()
    {
        return "clear";
    }

    /**
     * @PostMapping("updateUser")
     * @PreAuthorize(value="hasRole('user')")
     * @return User|null
     * @throws \ESD\Plugins\Mysql\MysqlException
     * @throws \ESD\Plugins\Validate\ValidationException
     * @throws \ESD\Core\Exception
     */
    public function updateUser()
    {
        $data = json_decode($this->request->getBody()->getContents(), true);
        return $this->userService->updateUser(new User($data));
    }

    /**
     * @PostMapping("insertUser")
     * @RequestBody("user")
     * @param User $user
     * @return User
     * @throws \ESD\Plugins\Validate\ValidationException
     * @throws \ESD\Plugins\Mysql\MysqlException
     */
    public function insertUser(User $user)
    {
        $user->insert();
        return $user;
    }
}
