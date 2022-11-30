<?php

namespace App\Service;

use App\Entity\Anomalie;
use App\Entity\Election;
use App\Entity\Votant;
use App\Entity\Vote;
use App\Repository\AnomalieRepository;
use App\Repository\CandidatRepository;
use App\Repository\ConcoursRepository;
use App\Repository\ElectionRepository;
use App\Repository\FamilleRepository;
use App\Repository\ScrutinRepository;
use App\Repository\VotantRepository;
use App\Repository\VoteRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class Utility
{
    private ConcoursRepository $concoursRepository;
    private FamilleRepository $familleRepository;
    private VoteRepository $voteRepository;
    private UrlGeneratorInterface $urlGenerator;
    private RequestStack $requestStack;
    private VotantRepository $votantRepository;
    private AnomalieRepository $anomalieRepository;
    private ScrutinRepository $scrutinRepository;
    private CandidatRepository $candidatRepository;
    private ElectionRepository $electionRepository;

    public function __construct(ConcoursRepository $concoursRepository, FamilleRepository $familleRepository, VoteRepository $voteRepository,
                                UrlGeneratorInterface $urlGenerator, RequestStack $requestStack, VotantRepository $votantRepository,
                                AnomalieRepository $anomalieRepository, ScrutinRepository $scrutinRepository, CandidatRepository $candidatRepository,
                                ElectionRepository $electionRepository
    )
    {
        $this->concoursRepository = $concoursRepository;
        $this->familleRepository = $familleRepository;
        $this->voteRepository = $voteRepository;
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;
        $this->votantRepository = $votantRepository;
        $this->anomalieRepository = $anomalieRepository;
        $this->scrutinRepository = $scrutinRepository;
        $this->candidatRepository = $candidatRepository;
        $this->electionRepository = $electionRepository;
    }

    /**
     * @param $string
     * @return \Symfony\Component\String\AbstractUnicodeString
     */
    public function slugify($string)
    {
        $slugify = new AsciiSlugger();
        return $slugify->slug(strtolower($string));
    }

    /**
     * Generation du code de concours
     *
     * @param $entity
     * @return mixed
     */
    public function codeConcours($entity): mixed
    {
        $verif = $this->concoursRepository->findOneBy([],['id'=>"DESC"]);
        if (!$verif) $code = 1;
        else $code = (int) $verif->getId() +1;

        $reference = "S".$code;
        $entity->setCode($reference);

        return $entity;
    }

    /**
     * @param $entity
     * @return mixed
     */
    public function codeFamille($entity): mixed
    {
        $verif = $this->familleRepository->findOneBy([],['id'=>'DESC']);
        if (!$verif) $id = 1;
        else $id = (int)$verif->getId() + 1;

        if (10 > $id) $code = '0'.$id;
        else $code = $id;

        // code du concours
        $concours = $entity->getConcours()->getCode();

        $entity->setCode($concours.''.$code);//dd($entity);

        return $entity;

    }

    /**
     * @param $famille
     * @param $telephone
     * @return bool
     */
    public function vote($famille, $telephone): bool
    {
        $concours =  $this->concoursRepository->findOneBy(['id'=>$famille->getConcours()]);
        $verif = $this->voteRepository->findOneBy(['concours' => $concours->getId(), 'telephone'=>$telephone]);
        if ($verif) return false;

        // Enregistrement
        $vote = new Vote();
        $vote->setTelephone($telephone);
        $vote->setFamille($famille);
        $vote->setConcours($concours);

        $this->voteRepository->save($vote, true);

        return true;
    }

    /**
     * verification de vote du telephone a ce concours
     *
     * @param $famille
     * @param $telephone
     * @return bool
     */
    public function verificationVote($famille, $telephone): bool
    {
        $concours =  $this->concoursRepository->findOneBy(['id'=>$famille->getConcours()]);
        $verif = $this->voteRepository->findOneBy(['concours' => $concours->getId(), 'telephone'=>$telephone]);
        if ($verif) return true;
        else return false;
    }

    public function listFamilleByConcoursActif()
    {
        $familles = $this->familleRepository->findByConcoursActif(); //dd($familles);
        $list=[]; $i=0;

        foreach ($familles as $famille){
            $list[$i++]=[
                'id' => $famille->getId(),
                'nom' => $famille->getNom(),
                'code' => $famille->getCode(),
                'media' => $famille->getMedia(),
                'slug' => $famille->getSlug(),
                'commune_id' => $famille->getCommune()->getId(),
                'commune_nom' => $famille->getCommune()->getNom(),
                'commune_slug' => $famille->getCommune()->getSlug(),
                'concours_id' => $famille->getConcours()->getId(),
                'concours_nom' => $famille->getConcours()->getNom(),
                'concours_code' => $famille->getConcours()->getCode(),
                'concours_debut' => $famille->getConcours()->getDebut(),
                'concours_fin' => $famille->getConcours()->getFin(),
                'concours_slug' => $famille->getConcours()->getSlug(),
                'vote' => count($famille->getVotes()),
                'vote_total' => count($famille->getConcours()->getVotes())
            ];
        }

        return $list;
    }

    public function classement()
    {
        $familles = $this->familleRepository->findByConcoursActif();
        $lists=[]; $i=0; $totalVote=1;
        foreach ($familles as $famille){
            $lists[$famille->getId()]=count($famille->getVotes());
            $totalVote = count($famille->getConcours()->getVotes());
        }
        arsort($lists); //dd($lists[0]);
        $rang=[]; $j=0;
        if (!$totalVote) $totalVote=1;
        foreach ($lists as $key => $value){
            $couple = $this->familleRepository->findOneBy(['id'=> (int)$key]);
            $vote = count($couple->getVotes());
            $rang[$j++]=[
                'id' => $couple->getId(),
                'nom' => $couple->getNom(),
                'media' => $couple->getMedia(),
                'vote' => $vote,
                'pourcentage' => round($vote * 100 / $totalVote, 2),
                'commune' => $couple->getCommune()->getNom()
            ];

        }

        return $rang;
    }

    /**
     * Liste des votes concernants un conours
     *
     * @param $concours
     * @return array
     */
    public function listVoteParConours($concours): array
    {
        $votes=[]; $i=0;
        foreach ($concours->getVotes() as $vote){
            $votes[$i++]=[
                'loop_index' => $i,
                'famille' => $vote->getFamille()->getNom(),
                'telephone' => "<a href=".$this->urlGenerator->generate('app_backend_vote_delete',['id' => $vote->getId()]).">".$vote->getTelephone()."</a>",
                'date' => $vote->getCreatedAt()->format("Y-m-d H:i:s"),
                'concours' => $vote->getConcours()->getNom(),
                'id' => $vote->getId(),
            ];
        }

        return $votes;
    }

    /**
     * Gestion de la fréquence de vote
     *
     * @param $famille
     * @return bool
     */
    public function adresseIp($famille): bool
    {
        //dd($this->requestStack->getMainRequest()->getClientIps());
        $ip = $this->requestStack->getMainRequest()->getClientIp();

        // On affecte les nouvelles valeurs
        $votant = new Votant();
        $votant->setIp($ip);
        $votant->setNombre(1);
        //$votant->setCreatedAt(date('Y-m-d H:i:s'));
        $votant->setFamille($famille);

        // Si le dernier vote des 3 est moins de 90min alors echec
        $adresse = $this->votantRepository->findOneBy(['ip' => $ip, 'famille' => $famille->getId()], ['id' => 'DESC']);
        if ($adresse){
            $temps_attente = strtotime('120 minutes ago');
            $dernier_vote = strtotime($adresse->getCreatedAt()->format('Y-m-d H:i:s'));
            //$difference = $temps_attente - $dernier_vote; dd($difference);

            if ($adresse->getNombre() > 9 and $dernier_vote > $temps_attente)
                return false;
            else
                if ($adresse->getNombre() < 10) $votant->setNombre($adresse->getNombre()+1);
                //$votant->setNombre();
        }

        // On instancie le nouveau votant
        $this->votantRepository->save($votant, true);

        return true;

    }

    public function listeVotant(): array
    {
        $votants = $this->votantRepository->findList();
        $list=[]; $i=0;
        foreach ($votants as $votant){
            $list[$i++] =[
                'loop_index' => $i,
                'id' => $votant->getId(),
                'ip' => '<a href="http://ip-api.com/json/'.$votant->getIp().'?fields=status,message,continent,country,countryCode,region,regionName,city,zip,lat,lon,timezone,isp,org,as,mobile,query" target="_blank">'.$votant->getIp().'</a>',
                'nombre' => $votant->getNombre(),
                'date' => $votant->getCreatedAt()->format('Y-m-d H:i:s'),
                'famille' => $votant->getFamille()->getNom()
            ];
        }

        return $list;
    }

    public function addAnomalie(): bool
    {
        // Rechercher dans la base de données la dernière anomalie
        $last_anomalie = $this->anomalieRepository->findOneBy([],['id'=>"DESC"]);
        if ($last_anomalie) $id = $last_anomalie->getId();
        else $id = 0;

        // extraire la liste des votes à partir de la dernière anomalie
        $votes = $this->voteRepository->findAnomalie($id); //dd($votes);

        if (!$votes) return false;

        $list=[];$i=0;
        foreach ($votes as $vote){ //dd(!is_numeric($vote->getTelephone()));
            $i++;
            $anomalie = new Anomalie();
            if (!is_numeric($vote->getTelephone())){
                // enregistrer le vote contenant anomalie dans la table anomalie
                $anomalie->setTelephone($vote->getTelephone());
                $anomalie->setCreatedAt($vote->getCreatedAt());
                $anomalie->setConcours($vote->getConcours());
                $anomalie->setFamille($vote->getFamille());
                $anomalie->setPosition($vote->getId());
                $this->anomalieRepository->save($anomalie, true);

                // Supprimer la ligne du vote contenant l'anomalie
                $this->voteRepository->remove($vote, true);
            }

            if ($i===1000){
                $anomalie->setConcours($vote->getConcours());
                $anomalie->setFamille($vote->getFamille());
                $anomalie->setTelephone(0000000000);
                $anomalie->setPosition($vote->getId());
                $anomalie->setCreatedAt($vote->getCreatedAt());
                $this->anomalieRepository->save($anomalie, true);

                //break;
            }

        }
        //dd($list);
        return true;
    }

    /**
     * Liste des anomalies
     *
     * @return array
     */
    public function listAnomalie(): array
    {
        $anomalies = $this->anomalieRepository->findAll();
        $list=[]; $i=0;
        foreach ($anomalies as $anomalie){
            if ($anomalie->getTelephone() !== 0){
                $list[$i++]=[
                    'loop_index' => $i,
                    'famille' => $anomalie->getFamille()->getNom(),
                    'telephone' => $anomalie->getTelephone(),
                    'date' => $anomalie->getCreatedAt()->format("Y-m-d H:i:s"),
                    'concours' => $anomalie->getConcours()->getNom(),
                    'id' => $anomalie->getId(),
                ];
            }

        }

        //$this->requestStack->getParentRequest()->fla
        return $list;
    }

    /**
     * Liste des candidats selon le scrutin en cours
     *
     * @return array
     */
    public function scrutinEnCours(): array
    {
        $candidats =  $this->candidatRepository->findByScrutin();
        $list=[]; $i=0;
        foreach ($candidats as $candidat){
            $list[$i++]=[
                'id' => $candidat->getId(),
                'nom' => $candidat->getNom(),
                'slug' => $candidat->getSlug(),
                'media' => $candidat->getMedia(),
                'commune' => $candidat->getCommune()->getNom(),
                'scrutin' => $candidat->getScrutin()->getNom(),
            ];
        } //dd($list);

        return $list;
    }

    public function election($coupleId, $operation)
    {
        // Verification dans la base de données de non-existence de la session
        if ($this->electionRepository->findOneBy(['operation'=>$operation]))
            return false;

        $candidat = $this->candidatRepository->findOneBy(['id' => $coupleId]);
        //$scrutin = $this->scrutinRepository->findOneBy(['id' => $candidat->getScrutin()->getId()]);

        // On instancie l'entité election
        $election = new Election();
        $election->setOperation($operation);
        $election->setCandidat($candidat);
        $election->setScrutin($candidat->getScrutin());

        $this->electionRepository->save($election, true);

        return $candidat;
    }
}