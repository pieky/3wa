<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MembersController extends Controller
{
    private $members;

    public function __construct()
    {
        $this->members = $this->getMembers();
    }

    /**
     * @Route("/", name="app.members.index")
     */
    public function listAction()
    {
        return $this->render('members/index.html.twig', [
            'members' => $this->members
        ]);
    }

    /**
     * @Route("/member/{id}",
     *     name="app.members.detail"
     * )
     */
    public function detailAction($id)
    {
        dump($this->members);
        $member = $this->members[$id];
        return $this->render('members/detail.html.twig', [
            'id' => $id,
            'member' => $member
        ]);
    }

    /**
     * @Route("/member/form/create",
     *     name="app.members.form"
     * )
     */
    public function formAction()
    {
        return $this->render('members/form.html.twig');
    }

    /**
     * @Route("/member/form/new",
     *     name="app.members.form.new"
     * )
     */
    public function newAction(Request $request)
    {
        $firstName = $request->request->get('firstName');
        $lastName = $request->request->get('lastName');
        $email = $request->request->get('email');

        $countMember = count($this->members)+1;

        $member = [
              'firstName' => $firstName,
              'lastName' => $lastName,
              'email' => $email,
              'img' => 'http://lorempixel.com/400/200/people/'.$countMember
        ];
        $this->members['member'.$countMember] = $member;

        dump($this->members);

        file_put_contents(realpath(__DIR__.'/../../../web/json/members.json'), json_encode($this->members));

        return $this->render('members/new.html.twig', [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'countMember' => $countMember
        ]);
    }

    /**
     * @return mixed
     * Fetch member list in json file
     */
    public function getMembers(){
        $members = json_decode(file_get_contents(realpath(__DIR__.'/../../../web/json/members.json')), true);
        return $members;
    }

}
